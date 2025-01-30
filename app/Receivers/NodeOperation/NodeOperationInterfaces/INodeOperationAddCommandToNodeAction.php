<?php

namespace App\Receivers\NodeOperation\NodeOperationInterfaces;

use App\Models\MnemoSchemaNodeCommand;

interface INodeOperationAddCommandToNodeAction extends INodeOperationAction
{
    /**
     * Add command to node
     * @param array $request
     * @return MnemoSchemaNodeCommand
     */
    public function addCommandToNode(array $request): MnemoSchemaNodeCommand;
}
