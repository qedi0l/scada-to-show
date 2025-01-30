<?php

namespace App\Commands\NodeOperation;

use App\Commands\AbstractCommand;
use App\Receivers\NodeOperation\AddNodeToSchemaReceiver;
use App\Receivers\NodeOperation\DeleteNodeFromSchemaReceiver;
use App\Repositories\NodeRepository;
use App\Repositories\SchemaRepository;
use Exception;
use Illuminate\Http\Request;
use Throwable;

class NodeOperationAddNode extends AbstractCommand
{
    /**
     * Add node
     * @return void
     * @throws Exception
     * @throws Throwable
     */
    public function execute(): void
    {
        // Validate
        $this->request->validate([
            'data.node.title' => 'required|string',
            'data.node.group_id' => 'required|integer',
            'data.node.type' => 'required|string',
            'data.node.schema_name' => 'required|string',
            'data.node.options.hardware_code' => 'nullable|integer',
            'data.node.options.parameter_code' => 'nullable|integer',
            'data.node.options.z_index' => 'nullable|integer',
            'data.node.options.appearance.width' => 'required|integer',
            'data.node.options.appearance.height' => 'required|integer',
            'data.node.options.geometry.x' => 'required|integer',
            'data.node.options.geometry.y' => 'required|integer',
            'data.node.options.link.schema_name' => 'required_if:data.node.type,link|string',
        ]);

        // Define Schema
        $schemaRepository = new SchemaRepository();
        $schema = $schemaRepository->getByName($this->request->input('data.node.schema_name'));
        $this->setSchemaId($schema->getKey());

        // Execute
        $receiver = new AddNodeToSchemaReceiver();
        $node = $receiver->addNodeToSchema($this->request->toArray());

        // Set Changes and Response Data
        $this
            ->setChanges([
                'node_id' => $node->getKey()
            ])
            ->setResponseData([
                'node_id' => $node->getKey()
            ]);
    }

    /**
     * Undo adding node
     * @return void
     * @throws Throwable
     */
    public function undo(): void
    {
        // Validate
        $this->request->validate([
            'node_id' => 'required|integer',
        ]);
        $nodeId = $this->request->input('node_id');

        // Define Schema
        $nodeRepository = new NodeRepository();
        $node = $nodeRepository->getById($nodeId);
        $this->setSchemaId($node->schema_id);

        // Execute
        $receiver = new DeleteNodeFromSchemaReceiver();
        $request = new Request([
            'data' => [
                'node_id' => $nodeId
            ]
        ]);
        $receiver->deleteNodeFromSchema($request->toArray());
    }
}
