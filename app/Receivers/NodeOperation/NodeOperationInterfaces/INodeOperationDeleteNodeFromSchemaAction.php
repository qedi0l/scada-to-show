<?php

namespace App\Receivers\NodeOperation\NodeOperationInterfaces;

interface INodeOperationDeleteNodeFromSchemaAction extends INodeOperationAction
{
    /**
     * Delete node from schema
     * @param array $request
     * @return bool|null
     */
    public function deleteNodeFromSchema(array $request): ?bool;
}
