<?php

namespace App\Receivers\NodeOperation\NodeOperationInterfaces;

use App\Models\MnemoSchemaNodeOptions;

interface INodeOperationAddLabelToNodeAction extends INodeOperationAction
{
    /**
     * Add label to node
     * @param array $request
     * @return MnemoSchemaNodeOptions
     */
    public function addLabelToNode(array $request): MnemoSchemaNodeOptions;
}
