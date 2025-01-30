<?php

namespace App\Receivers\NodeOperation\NodeOperationInterfaces;


use Illuminate\Support\Collection;

interface INodeOperationAddMultipleChildNodesAction extends INodeOperationAction
{
    /**
     * Add multiple child nodes
     * @param array $request
     * @return Collection
     */
    public function addMultipleChildNodes(array $request): Collection;
}
