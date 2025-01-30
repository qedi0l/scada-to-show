<?php

namespace App\Receivers\NodeOperation;

use App\Receivers\NodeOperation\NodeOperationInterfaces\INodeOperationDeleteNodeFromSchemaAction;
use App\Repositories\NodeRepository;
use Throwable;

class DeleteNodeFromSchemaReceiver implements INodeOperationDeleteNodeFromSchemaAction
{
    /**
     * @param array $request
     * @return bool|null
     * @throws \Exception
     */
    public function deleteNodeFromSchema(array $request): ?bool
    {
        $nodeRepository = new NodeRepository();

        return $nodeRepository->destroy($request['data']['node_id']);
    }

}
