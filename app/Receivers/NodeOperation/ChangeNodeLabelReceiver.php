<?php

namespace App\Receivers\NodeOperation;

use App\DTO\NodeOptionsDto;
use App\Models\MnemoSchemaNodeOptions;
use App\Receivers\NodeOperation\NodeOperationInterfaces\INodeOperationChangeNodeLabelAction;
use App\Repositories\NodeOptionsRepository;

class ChangeNodeLabelReceiver implements INodeOperationChangeNodeLabelAction
{
    protected NodeOptionsRepository $nodeOptionsRepository;

    public function __construct()
    {
        $this->nodeOptionsRepository = new NodeOptionsRepository();
    }

    /**
     * @param array $request
     * @return MnemoSchemaNodeOptions
     */
    public function changeNodeLabel(array $request): MnemoSchemaNodeOptions
    {
        $requestData = $request['data'];

        $nodeOptions = $this->nodeOptionsRepository->getByNodeId($requestData['node_id']);

        $dto = new NodeOptionsDto(
            nodeId: $nodeOptions->node_id,
            zIndex: $nodeOptions->z_index,
            parameterCode: $nodeOptions->parameter_code,
            hardwareCode: $nodeOptions->hardware_code,
            parentId: $nodeOptions->parent_id,
            label: $requestData['label'],
        );
        return $this->nodeOptionsRepository->update($nodeOptions, $dto);
    }
}
