<?php

namespace App\Commands\NodeOperation;

use App\Commands\AbstractCommand;
use App\Receivers\NodeOperation\AddNodeFromNodeTypeGroupReceiver;
use App\Repositories\SchemaRepository;
use Throwable;

class NodeOperationAddNodeFromNodeTypeGroup extends AbstractCommand
{

    /**
     * Add node from node type group
     * @return void
     * @throws Throwable
     */
    public function execute(): void
    {
        // Validate
        $this->request->validate([
            'data.schema_name' => 'required|string',
            'data.node_type' => 'required|string',
            'data.node_link' => 'array',
            'data.node_link.schema_name' => 'required_if:data.node_type,link|string',
        ]);

        // Define Schema
        $schemaRepository = new SchemaRepository();
        $schema = $schemaRepository->getByName($this->request->input('data.schema_name'));
        $this->setSchemaId($schema->getKey());

        // Execute
        $receiver = new AddNodeFromNodeTypeGroupReceiver();
        $node = $receiver->addNodeFromNodeTypeGroup($this->request->toArray());

        // Set Changes and Response Data
        $this->setResponseData(['node_id' => $node->getKey()]);
    }

    /**
     * Undo adding node
     * @return void
     */
    public function undo(): void
    {
        // Validate

        // Define Schema

        // Execute
    }
}
