<?php

namespace App\Receivers\NodeOperation;

use App\DTO\NodeOptionsDto;
use App\Models\MnemoSchemaNodeOptions;
use App\Receivers\NodeOperation\NodeOperationInterfaces\INodeOperationDeleteNodeLabelAction;
use App\Repositories\NodeOptionsRepository;

class DeleteNodeLabelReceiver implements INodeOperationDeleteNodeLabelAction
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
    public function deleteNodeLabel(array $request): MnemoSchemaNodeOptions
    {
        $nodeOptions = $this->nodeOptionsRepository->getByNodeId($request['data']['node_id']);

        $dto = new NodeOptionsDto(
            nodeId: $nodeOptions->node_id,
            zIndex: $nodeOptions->z_index,
            parameterCode: $nodeOptions->parameter_code,
            hardwareCode: $nodeOptions->hardware_code,
            parentId: $nodeOptions->parent_id,
            label: null,
        );
        return $this->nodeOptionsRepository->update($nodeOptions, $dto);
    }
}

