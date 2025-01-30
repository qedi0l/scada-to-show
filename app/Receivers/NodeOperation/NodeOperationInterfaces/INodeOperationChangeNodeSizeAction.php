<?php

namespace App\Receivers\NodeOperation\NodeOperationInterfaces;

interface INodeOperationChangeNodeSizeAction extends INodeOperationAction
{
    /**
     * Change node size
     * @param array $request
     * @return string
     */
    public function changeNodeSize(array $request): string;
}
