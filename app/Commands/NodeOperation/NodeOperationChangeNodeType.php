<?php

namespace App\Commands\NodeOperation;

use App\Commands\AbstractCommand;
use App\DTO\NodeDto;
use App\Receivers\NodeOperation\ChangeNodeTypeReceiver;
use App\Repositories\NodeRepository;
use App\Repositories\NodeTypeRepository;
use Exception;
use Throwable;

class NodeOperationChangeNodeType extends AbstractCommand
{
    /**
     * Change node type
     * @return void
     * @throws Exception|Throwable
     */
    public function execute(): void
    {
        // Validate
        $this->request->validate([
            'data.node_id' => 'required|integer',
            'data.node_type' => 'required|string'
        ]);
        $nodeId = $this->request->input('data.node_id');
        $nodeRepository = new NodeRepository();
        $node = $nodeRepository->getById($nodeId);
        $nodeTypeRepository = new NodeTypeRepository();
        $nodeType = $nodeTypeRepository->getByType($this->request->input('data.node_type'));

        // Define Schema
        $this->setSchemaId($node->schema_id);

        // Execute
        $receiver = new ChangeNodeTypeReceiver();
        $receiver->changeNodeType($this->request->toArray());

        // Set Changes and Response Data
        $this
            ->setChanges([
                'node_id' => $node->getKey(),
                'original_type_id' => $node->type_id,
                'new_type' => $nodeType->getKey(),
            ])
            ->setResponseData(['message' => 'Node type changed successfully']);
    }

    /**
     * Undo of changing node type
     * @return void
     * @throws Exception
     */
    public function undo(): void
    {
        // Validate
        $this->request->validate([
            'node_id' => 'required|integer',
            'original_type_id' => 'required|integer',
            'new_type' => 'required|integer',
        ]);
        $nodeId = $this->request->input('node_id');
        $nodeRepository = new NodeRepository();
        $node = $nodeRepository->getById($nodeId);

        // Define Schema
        $this->setSchemaId($node->schema_id);

        // Execute
        $dto = new NodeDto(
            title: $node->title,
            schemaId: $node->schema_id,
            groupId: $node->group_id,
            typeId: $this->request->input('original_type_id'),
        );
        $nodeRepository->update($node, $dto);
    }
}
