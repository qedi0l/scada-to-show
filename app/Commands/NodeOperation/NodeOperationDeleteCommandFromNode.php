<?php

namespace App\Commands\NodeOperation;

use App\Commands\AbstractCommand;
use App\Receivers\NodeOperation\AddCommandToNodeReceiver;
use App\Receivers\NodeOperation\DeleteCommandFromNodeReceiver;
use App\Repositories\NodeRepository;
use Exception;

class NodeOperationDeleteCommandFromNode extends AbstractCommand
{
    /**
     * Delete command from node
     * @return void
     * @throws Exception
     */
    public function execute(): void
    {
        // Validate
        $this->request->validate([
            'data.node_id' => ['required', 'integer'],
            'data.parameter_code' => ['required', 'integer'],
        ]);
        $nodeId = $this->request->input('data.node_id');
        $parameterCode = $this->request->input('data.parameter_code');
        $nodeRepository = new NodeRepository();
        $node = $nodeRepository->getById($nodeId);

        // Define Schema
        $this->setSchemaId($node->schema_id);

        // Execute
        $receiver = new DeleteCommandFromNodeReceiver();
        $receiver->deleteCommandFromNode($this->request->toArray());

        // Set Changes and Response Data
        $this
            ->setChanges([
                'node_id' => $nodeId,
                'parameter_code' => $parameterCode
            ])
            ->setResponseData(null);
    }

    /**
     * Undo deleting of command from node
     * @return void
     * @throws Exception
     */
    public function undo(): void
    {
        // Validate
        $this->request->validate([
            'node_id' => ['required', 'integer'],
            'parameter_code' => ['required', 'integer'],
        ]);
        $nodeId = $this->request->input('node_id');
        $parameterCode = $this->request->input('parameter_code');
        $nodeRepository = new NodeRepository();
        $node = $nodeRepository->getById($nodeId);

        // Define Schema
        $this->setSchemaId($node->schema_id);

        // Execute
        $receiver = new AddCommandToNodeReceiver();
        $requestData = [
            'data' => [
                'node_id' => $nodeId,
                'parameter_code' => $parameterCode
            ]
        ];
        $receiver->addCommandToNode($requestData);
    }
}
