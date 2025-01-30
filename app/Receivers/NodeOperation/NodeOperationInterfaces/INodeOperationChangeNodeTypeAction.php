<?php

namespace App\Receivers\NodeOperation\NodeOperationInterfaces;

use App\Models\MnemoSchemaNode;

interface INodeOperationChangeNodeTypeAction extends INodeOperationAction
{
    /**
     * Change node type
     * @param array $request
     * @return MnemoSchemaNode
     */
    public function changeNodeType(array $request): MnemoSchemaNode;
}
