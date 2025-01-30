<?php

namespace App\Receivers\NodeOperation\NodeOperationInterfaces;

use App\Models\MnemoSchemaNode;

interface INodeOperationAddNodeFromNodeTypeGroupAction extends INodeOperationAction
{
    /**
     * Add node from node type group
     * @param array $request
     * @return MnemoSchemaNode
     */
    public function addNodeFromNodeTypeGroup(array $request): MnemoSchemaNode;
}
