<?php

namespace App\Receivers\NodeOperation;

use App\DTO\NodeAppearanceDto;
use App\DTO\NodeDto;
use App\DTO\NodeGeometryDto;
use App\DTO\NodeLinkDto;
use App\DTO\NodeOptionsDto;
use App\Models\MnemoSchemaNode;
use App\Receivers\NodeOperation\NodeOperationInterfaces\INodeOperationAddNodeToSchemaAction;
use App\Repositories\NodeRepository;
use App\Repositories\NodeTypeRepository;
use App\Repositories\SchemaRepository;
use Throwable;

class AddNodeToSchemaReceiver implements INodeOperationAddNodeToSchemaAction
{
    /**
     * @param array $request
     * @return MnemoSchemaNode
     * @throws \Exception
     */
    public function addNodeToSchema(array $request): MnemoSchemaNode
    {
        $requestData = $request['data'];
        $nodeData = $requestData['node'];
        $nodeOptions = $nodeData['options'];
        $nodeAppearance = $nodeOptions['appearance'];
        $nodeGeometry = $nodeOptions['geometry'];

        $schemaRepository = new SchemaRepository();
        $nodeTypeRepository = new NodeTypeRepository();
        $nodeRepository = new NodeRepository();

        $schema = $schemaRepository->getByName($nodeData['schema_name']);
        $nodeType = $nodeTypeRepository->getByType($nodeData['type']);

        if ($nodeData['type'] === 'link') {
            $linkSchema = $schemaRepository->getByName($nodeOptions['link']['schema_name']);
            $nodeLinkDto = new NodeLinkDto(nodeId: 0, schemaId: $linkSchema->getKey());
        } else {
            $nodeLinkDto = null;
        }

        $nodeDto = new NodeDto(
            title: $nodeData['title'],
            schemaId: $schema->getKey(),
            groupId: $nodeData['group_id'],
            typeId: $nodeType->getKey(),
            options: new NodeOptionsDto(
                nodeId: 0,
                zIndex: $nodeOptions['z_index'] ?? $schemaRepository->getMaxZIndex($schema->getKey()) + 1,
                parameterCode: array_key_exists('parameter_code', $nodeOptions) ? $nodeOptions['parameter_code'] : null,
                hardwareCode: array_key_exists('hardware_code', $nodeOptions) ? $nodeOptions['hardware_code'] : null,
            ),
            appearance: new NodeAppearanceDto(
                nodeId: 0,
                width: $nodeAppearance['width'],
                height: $nodeAppearance['height'],
            ),
            geometry: new NodeGeometryDto(
                nodeId: 0,
                x: $nodeGeometry['x'],
                y: $nodeGeometry['y'],
            ),
            link: $nodeLinkDto
        );
        return $nodeRepository->store($nodeDto);
    }
}
