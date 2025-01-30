<?php

namespace App\Services;

use App\Contracts\IAvailableCommands;
use App\Contracts\IScadaUINode;
use App\Contracts\IScadaUINodeAppearance;
use App\Contracts\IScadaUINodeGeometry;
use App\Exceptions\NodeTypeNotFoundException;
use App\Models\MnemoSchema;
use App\Models\MnemoSchemaNode;
use App\Models\MnemoSchemaNodeAppearance;
use App\Models\MnemoSchemaNodeOptions;
use App\Models\MnemoSchemaNodeType;
use App\Repositories\Filters\NodeFilter;
use App\Repositories\NodeOptionsRepository;
use App\Repositories\NodeRepository;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;

class ScadaUINodeService implements IScadaUINode
{
    protected NodeOptionsRepository $nodeOptionsRepository;

    private IScadaUINodeGeometry $geometry;
    private IScadaUINodeAppearance $appearance;

    private const DEFAULT_TYPE = 'default';

    public function __construct()
    {
        $this->nodeOptionsRepository = new NodeOptionsRepository();

        $this->appearance = App::make(IScadaUINodeAppearance::class);
        $this->geometry = App::make(IScadaUINodeGeometry::class);
    }

    /**
     * @param MnemoSchema $schema
     * @return array
     * @throws Exception
     */
    public function getServiceNodesBySchema(MnemoSchema $schema): array
    {
        $nodes = MnemoSchemaNode::query()
            ->where('schema_id', $schema->id)
            ->whereHas('node_type', function ($query) {
                $query->where('service_type', true);
            })
            ->get();

        return $nodes->map(function ($node) {
            return $this->getNodeData($node);
        })->toArray();
    }


    /**
     * @param MnemoSchemaNode $node
     * @return array
     */
    private function getNodeData(MnemoSchemaNode $node): array
    {
        return [
            'id' => $node->id,
            'title' => $node->title,
            'type_title' => $this->getNodeTypeTitle($node),
            'type_id' => $node->type_id,
            'type' => $this->getNodeType($node),
            'node_type_group_id' => $this->getNodeTypeGroupId($node->type_id),
            'group' => $node->group_id,
            'options' => $this->getNodeOptions($node),
            'svg' => $this->getNodeTypeSvg($node)
        ];
    }

    /**
     * @param MnemoSchemaNode $node
     * @return string
     */
    private function getNodeTypeTitle(MnemoSchemaNode $node): string
    {
        return MnemoSchemaNodeType::whereId($node->type_id)->first('type')->title ?? $this::DEFAULT_TYPE;
    }

    /**
     * @param MnemoSchemaNode $node
     * @return string|null
     */
    private function getNodeTypeSvg(MnemoSchemaNode $node): string|null
    {
        return MnemoSchemaNodeType::whereId($node->type_id)->value('svg');
    }

    /**
     * Get all options of node
     * @param MnemoSchemaNode $node
     * @return array
     */
    private function getNodeOptions(MnemoSchemaNode $node): array
    {
        $options = MnemoSchemaNodeOptions::whereNodeId($node->id)->firstOrFail();

        return [
            'appearance' => $this->appearance->getNodeAppearance($node),
            'geometry' => $this->geometry->getNodeGeometry($node),
            'value' => $options->value,
            'z_index' => $options->z_index,
            'parent_id' => $options->parent_id,
            'hardware_code' => $options->hardware_code,
            'parameter_code' => $options->parameter_code,
        ];
    }

    /**
     * @param MnemoSchemaNode $node
     * @return string
     */
    private function getNodeType(MnemoSchemaNode $node): string
    {
        return MnemoSchemaNodeType::whereId($node->type_id)->first('type')->type ?? $this::DEFAULT_TYPE;
    }

    /**
     * @param string $schemaName
     * @param $parentNodeId
     * @return JsonResponse
     */
    public function showHierarchyByMnemoSchema(string $schemaName, $parentNodeId): JsonResponse
    {
        $schema = MnemoSchema::whereName($schemaName)->first();
        $nodes = MnemoSchemaNode::whereSchemaId($schema->id)->get();
        $nodes->makeHidden(['created_at', 'updated_at', 'schema_id', 'type_id', 'group_id']);

        foreach ($nodes as $node) {
            $node->parent_id = $this->getNodeParentId($node);
            $node->min_svg = MnemoSchemaNodeAppearance::whereNodeId($node->id)->value('min_svg');
            $node->type = $this->getNodeType($node);
        }

        return $this->getChildren($nodes->toArray(), $parentNodeId);
    }

    /**
     * @param array $nodes
     * @param $parentNodeId
     * @return JsonResponse
     */
    private function getChildren(array $nodes, $parentNodeId): JsonResponse
    {
        $branch = [];
        foreach ($nodes as $node) {
            if ($node['parent_id'] == $parentNodeId) {
                $count = MnemoSchemaNodeOptions::query()->where('parent_id', $node['id'])->count();

                if ($count == 0) {
                    $node['isHasChildren'] = false;
                } else {
                    $node['isHasChildren'] = true;
                }
                $branch[$node['id']] = $node;
            }
        }

        $schemas = MnemoSchema::query()->select(['id', 'title'])->get();
        if ($parentNodeId == 0) {
            return response()->json([
                'nodes_info' => $branch,
                'pages' => $schemas
            ]);
        }

        return response()->json($branch);
    }

    /**
     * @param int $nodeId
     * @return MnemoSchemaNodeOptions
     * @throws Exception
     */
    public function increaseZIndexByOne(int $nodeId): MnemoSchemaNodeOptions
    {
        $nodeOptions = $this->nodeOptionsRepository->getByNodeId($nodeId);

        return $this->nodeOptionsRepository->updateZIndex($nodeOptions, $nodeOptions->z_index + 1);
    }

    /**
     * @param int $nodeId
     * @return MnemoSchemaNodeOptions
     * @throws Exception
     */
    public function decreaseZIndexByOne(int $nodeId): MnemoSchemaNodeOptions
    {
        $nodeOptions = $this->nodeOptionsRepository->getByNodeId($nodeId);

        return $this->nodeOptionsRepository->updateZIndex($nodeOptions, $nodeOptions->z_index - 1);
    }

    /**
     * @param int $nodeId
     * @return MnemoSchemaNodeOptions
     * @throws Exception
     */
    public function increaseZIndexToTheHighest(int $nodeId): MnemoSchemaNodeOptions
    {
        $maxZIndexOfNeighbours = $this->nodeOptionsRepository->getMaxZIndexOfNeighbourNodes($nodeId);

        $nodeOptions = $this->nodeOptionsRepository->getByNodeId($nodeId);

        return $this->nodeOptionsRepository->updateZIndex($nodeOptions, $maxZIndexOfNeighbours + 1);
    }

    /**
     * @param int $nodeId
     * @return MnemoSchemaNodeOptions
     * @throws Exception
     */
    public function decreaseZIndexToTheLowest(int $nodeId): MnemoSchemaNodeOptions
    {
        $minZIndexOfNeighbours = $this->nodeOptionsRepository->getMinZIndexOfNeighbourNodes($nodeId);

        $nodeOptions = $this->nodeOptionsRepository->getByNodeId($nodeId);

        return $this->nodeOptionsRepository->updateZIndex($nodeOptions, $minZIndexOfNeighbours - 1);
    }


    /**
     * @param MnemoSchemaNode $node
     * @return int|null
     */
    private function getNodeParentId(MnemoSchemaNode $node): int|null
    {
        $options = $this->getNodeOptions($node);

        return $options['parent_id'];
    }


    /**
     * @param int $nodeId
     * @return Collection
     */
    public function getChildNodes(int $nodeId): mixed
    {
        $filter = (new NodeFilter())
            ->setParentNodeId($nodeId)
            ->setHasNotEmptyParameterCode(true);

        return (new NodeRepository())
            ->index($filter)
            ->load(['options', 'node_type.group']);
    }

    /**
     * @param int $typeId
     * @return int
     */
    private function getNodeTypeGroupId(int $typeId): int
    {
        $groupId = MnemoSchemaNodeType::query()
            ->where('id', $typeId)
            ->first()
            ??
            MnemoSchemaNodeType::query()
                ->where('type', $this::DEFAULT_TYPE)
                ->first();

        if ($groupId != null) {
            return $groupId->node_type_group_id;
        }

        throw new NodeTypeNotFoundException($typeId);
    }

}
