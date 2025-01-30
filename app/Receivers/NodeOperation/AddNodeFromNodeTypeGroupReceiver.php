<?php

namespace App\Receivers\NodeOperation;


use App\DTO\NodeAppearanceDto;
use App\DTO\NodeDto;
use App\DTO\NodeGeometryDto;
use App\DTO\NodeLinkDto;
use App\DTO\NodeOptionsDto;
use App\Models\MnemoSchemaNode;
use App\Receivers\NodeOperation\NodeOperationInterfaces\INodeOperationAddNodeFromNodeTypeGroupAction;
use App\Repositories\NodeRepository;
use App\Repositories\NodeTypeRepository;
use App\Repositories\SchemaRepository;
use Throwable;

class AddNodeFromNodeTypeGroupReceiver implements INodeOperationAddNodeFromNodeTypeGroupAction
{
    protected SchemaRepository $schemaRepository;
    protected NodeRepository $nodeRepository;
    protected NodeTypeRepository $nodeTypeRepository;

    public function __construct()
    {
        $this->schemaRepository = new SchemaRepository();
        $this->nodeRepository = new NodeRepository();
        $this->nodeTypeRepository = new NodeTypeRepository();
    }

    /**
     * @param array $request
     * @return MnemoSchemaNode
     * @throws \Exception
     */
    public function addNodeFromNodeTypeGroup(array $request): MnemoSchemaNode
    {
        $requestData = $request['data'];

        $schema = $this->schemaRepository->getByName($requestData['schema_name']);
        $nodeType = $this->nodeTypeRepository->getByType($requestData['node_type']);

        if ($nodeType->type === 'link') {
            $linkSchema = $this->schemaRepository->getByName($requestData['node_link']['schema_name']);
            $nodeLinkDto = new NodeLinkDto(nodeId: 0, schemaId: $linkSchema->getKey());
        } else {
            $nodeLinkDto = null;
        }

        $maxZIndex = $this->schemaRepository->getMaxZIndex($schema->getKey());

        $nodeDto = new NodeDto(
            title: 'Нода ' . rand(),
            schemaId: $schema->getKey(),
            groupId: 1,
            typeId: $nodeType->getKey(),
            options: new NodeOptionsDTO(nodeId: 0, zIndex: $maxZIndex + 1),
            appearance: new NodeAppearanceDTO(nodeId: 0),
            geometry: new NodeGeometryDTO(nodeId: 0),
            link: $nodeLinkDto,
        );
        return $this->nodeRepository->store($nodeDto);
    }
}
