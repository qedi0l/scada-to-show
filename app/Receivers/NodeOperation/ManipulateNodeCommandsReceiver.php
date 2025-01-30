<?php

namespace App\Receivers\NodeOperation;

use App\Receivers\NodeOperation\NodeOperationInterfaces\INodeOperationManipulateNodeCommandsAction;
use App\Repositories\NodeCommandRepository;
use Throwable;

class ManipulateNodeCommandsReceiver implements INodeOperationManipulateNodeCommandsAction
{

    /**
     * @param array $request
     * @return true
     * @throws Throwable
     */
    public function manipulateNodeCommands(array $request): true
    {
        $requestData = $request['data'];

        $nodeCommandRepository = new NodeCommandRepository();

        $nodeCommandRepository->syncNodeParameterCodes($requestData['node_id'], $requestData['commands']);

        return true;
    }
}
