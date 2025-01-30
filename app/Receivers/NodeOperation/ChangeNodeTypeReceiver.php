<?php

namespace App\Receivers\NodeOperation;

use App\DTO\NodeDto;
use App\Models\MnemoSchemaNode;
use App\Receivers\NodeOperation\NodeOperationInterfaces\INodeOperationChangeNodeTypeAction;
use App\Repositories\NodeRepository;
use App\Repositories\NodeTypeRepository;
use Throwable;

class ChangeNodeTypeReceiver implements INodeOperationChangeNodeTypeAction
{
    protected NodeRepository $nodeRepository;
    protected NodeTypeRepository $nodeTypeRepository;

    public function __construct()
    {
        $this->nodeRepository = new NodeRepository();
        $this->nodeTypeRepository = new NodeTypeRepository();
    }

    /**
     * @param array $request
     * @return MnemoSchemaNode
     * @throws \Exception
     */
    public function changeNodeType(array $request): MnemoSchemaNode
    {
        $node = $this->nodeRepository->getById($request['data']['node_id']);
        $nodeType = $this->nodeTypeRepository->getByType($request['data']['node_type']);

        $dto = new NodeDto(
            title: $node->title,
            schemaId: $node->schema_id,
            groupId: $node->group_id,
            typeId: $nodeType->getKey(),
        );
        return $this->nodeRepository->update($node, $dto);
    }
}

