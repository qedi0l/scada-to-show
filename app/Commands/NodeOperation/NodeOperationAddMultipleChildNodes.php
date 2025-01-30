<?php

namespace App\Commands\NodeOperation;

use App\Commands\AbstractCommand;
use App\Receivers\NodeOperation\AddMultipleChildNodesReceiver;
use App\Repositories\NodeRepository;
use Throwable;

class NodeOperationAddMultipleChildNodes extends AbstractCommand
{

    /**
     * Add multiple child nodes
     * @return void
     * @throws Throwable
     */
    public function execute(): void
    {
        // Validate
        $this->request->validate([
            'data.schema_name' => 'required|string',
            'data.node_id' => 'required|integer',
            'data.node_types' => 'array',
            'data.signals' => 'array'
        ]);
        $nodeId = $this->request->input('data.node_id');

        // Define Schema
        $nodeRepository = new NodeRepository();
        $node = $nodeRepository->getById($nodeId);
        $this->setSchemaId($node->schema_id);

        // Execute
        $receiver = new AddMultipleChildNodesReceiver();
        $result = $receiver->addMultipleChildNodes($this->request->toArray());

        // Set Changes and Response Data
        $this->setResponseData($result);
    }

    /**
     * Undo adding of multiple child nodes
     * @return void
     */
    public function undo(): void
    {
        // Validate

        // Define Schema

        // Execute
    }
}
