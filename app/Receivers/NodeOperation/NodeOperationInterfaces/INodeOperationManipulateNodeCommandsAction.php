<?php

namespace App\Receivers\NodeOperation\NodeOperationInterfaces;

interface INodeOperationManipulateNodeCommandsAction extends INodeOperationAction
{
    /**
     * Manipulate node commands
     * @param array $request
     * @return true
     */
    public function manipulateNodeCommands(array $request): true;
}
