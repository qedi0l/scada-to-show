<?php

namespace App\Receivers\NodeOperation\NodeOperationInterfaces;

use App\Models\MnemoSchemaNodeOptions;

interface INodeOperationDeleteNodeLabelAction extends INodeOperationAction
{
    /**
     * Delete node label
     * @param array $request
     * @return MnemoSchemaNodeOptions
     */
    public function deleteNodeLabel(array $request): MnemoSchemaNodeOptions;
}
