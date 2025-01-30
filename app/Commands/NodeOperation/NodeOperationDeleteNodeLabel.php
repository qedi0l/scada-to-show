<?php

namespace App\Commands\NodeOperation;

use App\Commands\AbstractCommand;
use App\Receivers\NodeOperation\AddLabelToNodeReceiver;
use App\Receivers\NodeOperation\DeleteNodeLabelReceiver;
use App\Repositories\NodeOptionsRepository;
use App\Repositories\NodeRepository;
use Exception;

class NodeOperationDeleteNodeLabel extends AbstractCommand
{
    /**
     * Delete node label
     * @return void
     * @throws Exception
     */
    public function execute(): void
    {
        // Validate
        $this->request->validate([
            'data.node_id' => 'required|integer'
        ]);
        $nodeId = $this->request->input('data.node_id');
        $nodeRepository = new NodeRepository();
        $node = $nodeRepository->getById($nodeId);
        $nodeOptionsRepository = new NodeOptionsRepository();
        $nodeOptions = $nodeOptionsRepository->getByNodeId($nodeId);

        // Define Schema
        $this->setSchemaId($node->schema_id);

        // Execute
        $receiver = new DeleteNodeLabelReceiver();
        $receiver->deleteNodeLabel($this->request->toArray());

        // Set Changes and Response Data
        $this
            ->setChanges([
                'node_id' => $nodeId,
                'original_label' => $nodeOptions->label
            ])
            ->setResponseData(null);
    }


    /**
     * Undo deleting of node label
     * @return void
     * @throws Exception
     */
    public function undo(): void
    {
        // Validate
        $this->request->validate([
            'node_id' => 'required|integer',
            'original_label' => 'required|string',
        ]);
        $originalLabel = $this->request->input('original_label');
        $nodeId = $this->request->input('node_id');
        $nodeRepository = new NodeRepository();
        $node = $nodeRepository->getById($nodeId);

        // Define Schema
        $this->setSchemaId($node->schema_id);

        // Execute
        $receiver = new AddLabelToNodeReceiver();
        $request = [
            'data' => [
                'node_id' => $nodeId,
                'label' => $originalLabel
            ]
        ];

        $receiver->addLabelToNode($request);
    }
}
