<?php

namespace App\Commands\NodeOperation;

use App\Commands\AbstractCommand;
use App\DTO\NodeDto;
use App\Receivers\NodeOperation\ChangeNodeTitleReceiver;
use App\Repositories\NodeRepository;
use Exception;
use Throwable;

class NodeOperationChangeNodeTitle extends AbstractCommand
{
    /**
     * Change node title
     * @return void
     * @throws Exception
     * @throws Throwable
     */
    public function execute(): void
    {
        // Validate
        $this->request->validate([
            'data.node_id' => 'required|integer',
            'data.node_title' => 'required|string'
        ]);
        $nodeId = $this->request->input('data.node_id');
        $nodeRepository = new NodeRepository();
        $node = $nodeRepository->getById($nodeId);

        // Define Schema
        $this->setSchemaId($node->schema_id);

        // Execute
        $receiver = new ChangeNodeTitleReceiver();
        $updatedNode = $receiver->changeNodeTitle($this->request->toArray());

        // Set Changes and Response Data
        $this
            ->setChanges([
                'node_id' => $node->getKey(),
                'original_title' => $node->title,
                'new_title' => $this->request->input('data.node_title')
            ])
            ->setResponseData([
                'title' => $updatedNode->title,
            ]);
    }

    /**
     * Undo changing of node title
     * @return void
     * @throws Exception
     */
    public function undo(): void
    {
        // Validate
        $this->request->validate([
            'node_id' => 'required|integer',
            'original_title' => 'required|string',
            'new_title' => 'required|string',
        ]);
        $nodeId = $this->request->input('node_id');
        $nodeRepository = new NodeRepository();
        $node = $nodeRepository->getById($nodeId);

        // Define Schema
        $this->setSchemaId($node->schema_id);

        // Execute
        $dto = new NodeDto(
            title: $this->request->input('original_title'),
            schemaId: $node->schema_id,
            groupId: $node->group_id,
            typeId: $node->type_id,
        );
        $nodeRepository->update($node, $dto);
    }
}
