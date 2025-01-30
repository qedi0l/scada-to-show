<?php

namespace App\Receivers\NodeOperation\NodeOperationInterfaces;

use App\Models\MnemoSchemaNode;

interface INodeOperationChangeNodeTitleAction extends INodeOperationAction
{
    /**
     * Change node title
     * @param array $request
     * @return MnemoSchemaNode
     */
    public function changeNodeTitle(array $request): MnemoSchemaNode;
}
