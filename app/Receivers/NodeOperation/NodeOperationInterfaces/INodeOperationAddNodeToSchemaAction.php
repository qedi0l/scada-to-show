<?php

namespace App\Receivers\NodeOperation\NodeOperationInterfaces;

use App\Models\MnemoSchemaNode;

interface INodeOperationAddNodeToSchemaAction extends INodeOperationAction
{
    /**
     * Add node to schema
     * @param array $request
     * @return MnemoSchemaNode
     */
    public function addNodeToSchema(array $request): MnemoSchemaNode;
}
