<?php

namespace App\Receivers\NodeOperation;

use App\Contracts\ICatalogService;
use App\DTO\NodeAppearanceDto;
use App\DTO\NodeDto;
use App\DTO\NodeGeometryDto;
use App\DTO\NodeOptionsDto;
use App\Models\MnemoSchemaNode;
use App\Receivers\NodeOperation\NodeOperationInterfaces\INodeOperationAddMultipleChildNodesAction;
use App\Repositories\Filters\NodeFilter;
use App\Repositories\NodeRepository;
use App\Repositories\NodeTypeRepository;
use App\Repositories\SchemaRepository;
use App\Services\CatalogServices\CatalogSignalService;
use App\Services\CatalogServices\Models\MetaData;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Throwable;

class AddMultipleChildNodesReceiver implements INodeOperationAddMultipleChildNodesAction
{

    protected SchemaRepository $schemaRepository;
    protected NodeRepository $nodeRepository;
    protected NodeTypeRepository $nodeTypeRepository;

    private ICatalogService $signalService;

    public function __construct()
    {
        $this->schemaRepository = new SchemaRepository();
        $this->nodeRepository = new NodeRepository();
        $this->nodeTypeRepository = new NodeTypeRepository();

        $this->signalService = new CatalogSignalService();
    }


    /**
     * @param array $request
     * @return Collection
     * @throws Throwable
     * @throws \Random\RandomException
     */
    public function addMultipleChildNodes(array $request): Collection
    {
        $requestData = $request['data'];
        $signals = $requestData['signals'];
        $nodeTypes = $requestData['node_types'];

        $schema = $this->schemaRepository->getByName($requestData['schema_name']);
        $node = $this->nodeRepository->getById($requestData['node_id'])
            ->load(['options', 'geometry', 'children_options']);


        DB::beginTransaction();

        // Delete rest Nodes
        $filter = (new NodeFilter())
            ->setParentNodeId($node->id)
            ->setHasNoParameterCodes($signals);
        $this->nodeRepository
            ->index($filter)
            ->each(function (MnemoSchemaNode $node) {
                $this->nodeRepository->destroy($node);
            });

        // Update And Create Nodes
        $filter = (new NodeFilter())
            ->setParentNodeId($node->id);
        $nodes = $this->nodeRepository
            ->index($filter)
            ->load(['options']);

        foreach ($signals as $key => $signal) {
            $type = $nodeTypes[$key];

            $nodeType = $this->nodeTypeRepository->getByType($type);

            /** @var MnemoSchemaNode $node */
            $currentNode = $nodes->where('options.parameter_code', $signal)->first();

            if (is_null($currentNode)) {
                // Create
                $signalsFromCatalog = $this->signalService->postMetaData($node->options->hardware_code, [$signal]);

                if (!is_null($signalsFromCatalog)) {
                    /** @var MetaData $signalFrom */
                    $signalFrom = $signalsFromCatalog->first();
                    $signalName = $signalFrom->signalData->name;

                    $maxZIndex = $this->schemaRepository->getMaxZIndex($schema->getKey());

                    $nodeDto = new NodeDto(
                        title: $signalName,
                        schemaId: $schema->getKey(),
                        groupId: 1,
                        typeId: $nodeType->getKey(),
                        options: new NodeOptionsDTO(
                            nodeId: 0,
                            zIndex: $maxZIndex + 1,
                            parameterCode: $signal,
                            hardwareCode: $node->options->hardware_code,
                            parentId: $node->id,
                        ),
                        appearance: new NodeAppearanceDTO(nodeId: 0),
                        geometry: new NodeGeometryDTO(
                            nodeId: 0,
                            x: $node->geometry->x + random_int(-20, 20),
                            y: $node->geometry->y + random_int(-20, 20),
                        )
                    );
                    $this->nodeRepository->store($nodeDto);
                }

            } elseif ($currentNode->type_id !== $nodeType->getKey()) {
                // Update
                $this->nodeRepository->updateTypeId($currentNode, $nodeType->getKey());
            }
        }

        // Generate Response
        $filter = (new NodeFilter())
            ->setParentNodeId($node->id);
        $result = $this->nodeRepository
            ->index($filter)
            ->load(['options', 'geometry', 'node_type'])
            ->map(function (MnemoSchemaNode $node) {
                return [
                    'id' => $node->getKey(),
                    'parameter_code' => $node->options->parameter_code,
                    'hardware_code' => $node->options->hardware_code,
                    'position' => [
                        'x' => $node->geometry->x,
                        'y' => $node->geometry->y,
                    ],
                    'title' => $node->title,
                    'type' => $node->node_type->type,
                    'node_type_group_id' => $node->node_type->node_type_group_id,
                ];
            });

        DB::commit();

        return $result;
    }
}
