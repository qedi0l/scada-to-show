<?php

namespace App\Commands\NodeOperation;

use App\Commands\AbstractCommand;
use App\Receivers\NodeOperation\ManipulateNodeCommandsReceiver;
use App\Repositories\Filters\NodeCommandFilter;
use App\Repositories\NodeCommandRepository;
use App\Repositories\NodeRepository;
use Exception;
use Throwable;

class NodeOperationManipulateNodeCommands extends AbstractCommand
{
    /**
     * Manipulate node commands
     * @return void
     * @throws Exception
     * @throws Throwable
     */
    public function execute(): void
    {
        // Validate
        $this->request->validate([
            'data.node_id' => 'required|integer',
            'data.commands' => 'nullable|array'
        ]);
        $nodeId = $this->request->input('data.node_id');
        $nodeRepository = new NodeRepository();
        $node = $nodeRepository->getById($nodeId);
        $filter = (new NodeCommandFilter())->setNodeId($nodeId);
        $commandRepository = new NodeCommandRepository();
        $previousParameterCodes = $commandRepository->index($filter)
            ->pluck('parameter_code')
            ->toArray();

        // Define Schema
        $this->setSchemaId($node->schema_id);

        // Execute
        $receiver = new ManipulateNodeCommandsReceiver();
        $receiver->manipulateNodeCommands($this->request->toArray());

        // Set Changes and Response Data
        $this
            ->setChanges([
                'node_id' => $node->getKey(),
                'previous_commands' => $previousParameterCodes,
            ])
            ->setResponseData(null);
    }

    /**
     * Undo manipulating on node commands
     * @return void
     * @throws Throwable
     */
    public function undo(): void
    {
        // Validate
        $this->request->validate([
            'node_id' => 'required|integer',
            'previous_commands' => 'nullable|array'
        ]);
        $previousCommands = $this->request->input('previous_commands');
        $nodeId = $this->request->input('node_id');
        $nodeRepository = new NodeRepository();
        $node = $nodeRepository->getById($nodeId);

        // Define Schema
        $this->setSchemaId($node->schema_id);

        // Execute
        $receiver = new ManipulateNodeCommandsReceiver();
        $undoRequest = [
            'data' => [
                'node_id' => $nodeId,
                'commands' => $previousCommands,
            ],
        ];
        $receiver->manipulateNodeCommands($undoRequest);
    }
}
