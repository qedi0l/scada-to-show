<?php

namespace App\Receivers\NodeOperation\NodeOperationInterfaces;

interface INodeOperationDeleteCommandFromNodeAction extends INodeOperationAction
{
    /**
     * Delete command from node
     * @param array $request
     * @return mixed
     */
    public function deleteCommandFromNode(array $request): mixed;
}
