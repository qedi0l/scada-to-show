<?php

namespace App\Services;

use App\Contracts\INodeTypeService;
use App\Models\MnemoSchemaNode;
use App\Models\MnemoSchemaNodeType;
use App\Models\MnemoSchemaNodeTypeGroup;
use Doctrine\Common\Cache\Psr6\InvalidArgument;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Spatie\FlareClient\Http\Exceptions\NotFound;

class NodeTypeService implements INodeTypeService
{
    protected string $defaultNodeType = 'default';
    protected string $transparentNodeType = 'transparent';

    /**
     * @return array
     */
    public function getNodeTypes(): array
    {
        $types = MnemoSchemaNodeType::where('svg', "!=", null)->get();
        $data[] = null;
        foreach ($types as $type) {
            $data[$type->type] = $type->svg;
        }
        return $data;
    }

    /**
     * @param Request $request
     * @return Model|MnemoSchemaNodeType
     * @throws NotFound
     */
    public function createNodeType(Request $request): Model|MnemoSchemaNodeType
    {
        $request->validate([
            'type' => 'required|string',
            'hardware_type' => 'required|string',
            'title' => 'string',
            'svg' => 'required|string',
            'node_type_group_title' => 'required|string'
        ]);

        $nodeTypeGroup = MnemoSchemaNodeTypeGroup::where('title', $request['node_type_group_title'])
            ->first();

        if (!$nodeTypeGroup) {
            throw new NotFound('Node type group does not exist');
        }

        return MnemoSchemaNodeType::create([
            'type' => $request['type'],
            'hardware_type' => $request['hardware_type'],
            'title' => $request['title'] ?? null,
            'svg' => $request['svg'],
            'node_type_group_id' => $nodeTypeGroup->id
        ]);
    }

    /**
     * @param Request $request
     * @return Model|Collection|array|MnemoSchemaNodeType|null
     * @throws NotFound
     */
    public function updateNodeType(Request $request): Model|Collection|array|MnemoSchemaNodeType|null
    {
        $request->validate([
            'node_type_id' => 'required|integer',
            'type' => 'string',
            'hardware_type' => 'string',
            'title' => 'string',
            'svg' => 'string',
            'node_type_group_title' => 'string'
        ]);

        $nodeType = MnemoSchemaNodeType::find($request['node_type_id']);

        $nodeTypeGroup = MnemoSchemaNodeTypeGroup::where('title', $request['node_type_group_title'])
            ->first();

        if (!$nodeTypeGroup) {
            throw new NotFound('Node type group does not exist');
        }

        $nodeType->update([
            'type' => $request['type'] ?? $nodeType->type,
            'hardware_type' => $request['hardware_type'] ?? $nodeType->hardware_type,
            'title' => $request['title'] ?? $nodeType->title,
            'svg' => $request['svg'] ?? $nodeType->svg,
            'node_type_group_id' => $nodeTypeGroup->id ?? $nodeType->node_type_group_id
        ]);

        $nodeType->save();

        return $nodeType;
    }


    /**
     * @param int $nodeTypeId
     * @return void
     * @throws NotFound
     */
    public function deleteNodeType(int $nodeTypeId): void
    {
        $nodeTypeToDelete = MnemoSchemaNodeType::find($nodeTypeId);

        if (!$nodeTypeToDelete) {
            throw new NotFound("Node type does not exist");
        }


        $nodesToChangeType = MnemoSchemaNode::where('type_id', $nodeTypeId)->get();

        $defaultNodeType = MnemoSchemaNodeType::where('type', $this->defaultNodeType)
            ->first();

        $transparentNodeType = MnemoSchemaNodeType::where('type', $this->transparentNodeType)
            ->first();


        $nodesToChangeType->each(function ($item) use ($defaultNodeType) {
            $item->type_id = $defaultNodeType->id;
            $item->save();
        });

        if (($nodeTypeToDelete->type == $this->defaultNodeType) || ($nodeTypeToDelete->type == $transparentNodeType)) {
            throw new InvalidArgument("Can't delete Default or Transparent node types");
        }

        $nodeTypeToDelete->delete();
    }
}
