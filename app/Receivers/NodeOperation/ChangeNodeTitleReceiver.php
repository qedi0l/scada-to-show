<?php

namespace App\Receivers\NodeOperation;

use App\DTO\NodeDto;
use App\Models\MnemoSchemaNode;
use App\Receivers\NodeOperation\NodeOperationInterfaces\INodeOperationChangeNodeTitleAction;
use App\Repositories\NodeRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class ChangeNodeTitleReceiver implements INodeOperationChangeNodeTitleAction
{
    protected NodeRepository $nodeRepository;

    public function __construct()
    {
        $this->nodeRepository = new NodeRepository();
    }

    /**
     * @param array $request
     * @return MnemoSchemaNode
     * @throws \Exception
     */
    public function changeNodeTitle(array $request): MnemoSchemaNode
    {
        $requestData = $request['data'];

        $node = $this->nodeRepository->getById($requestData['node_id']);
        $dto = new NodeDto(
            title: $requestData['node_title'],
            schemaId: $node->schema_id,
            groupId: $node->group_id,
            typeId: $node->type_id,
        );
        return $this->nodeRepository->update($node, $dto);
    }
}
