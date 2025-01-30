<?php

namespace App\Receivers\NodeOperation;

use App\Receivers\NodeOperation\NodeOperationInterfaces\INodeOperationDeleteCommandFromNodeAction;
use App\Repositories\NodeCommandRepository;

class DeleteCommandFromNodeReceiver implements INodeOperationDeleteCommandFromNodeAction
{
    protected NodeCommandRepository $nodeCommandRepository;

    public function __construct()
    {
        $this->nodeCommandRepository = new NodeCommandRepository();
    }

    /**
     * @param array $request
     * @return mixed
     */
    public function deleteCommandFromNode(array $request): mixed
    {
        return $this->nodeCommandRepository
            ->destroyByNodeIdAndParameterCode(
                $request['data']['node_id'],
                $request['data']['parameter_code']
            );
    }
}
