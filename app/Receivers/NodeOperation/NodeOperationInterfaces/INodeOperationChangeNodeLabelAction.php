<?php

namespace App\Receivers\NodeOperation\NodeOperationInterfaces;

use App\Models\MnemoSchemaNodeOptions;

interface INodeOperationChangeNodeLabelAction extends INodeOperationAction
{
    /**
     * Change node label
     * @param array $request
     * @return MnemoSchemaNodeOptions
     */
    public function changeNodeLabel(array $request): MnemoSchemaNodeOptions;
}
