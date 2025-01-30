<?php

namespace App\Commands\NodeOperation;

use App\Commands\AbstractCommand;
use App\DTO\NodeOptionsDto;
use App\Receivers\NodeOperation\ChangeNodeLabelReceiver;
use App\Repositories\NodeOptionsRepository;
use App\Repositories\NodeRepository;
use Exception;

class NodeOperationChangeNodeLabel extends AbstractCommand
{
    /**
     * Change node label
     * @return void
     * @throws Exception
     */
    public function execute(): void
    {
        // Validate
        $this->request->validate([
            'data.node_id' => 'required|integer',
            'data.label' => 'required|string'
        ]);
        $nodeId = $this->request->input('data.node_id');
        $nodeRepository = new NodeRepository();
        $node = $nodeRepository->getById($nodeId);
        $nodeOptionsRepository = new NodeOptionsRepository();
        $nodeOptions = $nodeOptionsRepository->getByNodeId($nodeId);

        // Define Schema
        $this->setSchemaId($node->schema_id);

        // Execute
        $receiver = new ChangeNodeLabelReceiver();
        $receiver->changeNodeLabel($this->request->toArray());

        // Set Changes and Response Data
        $this
            ->setChanges([
                'node_id' => $nodeId,
                'original_label' => $nodeOptions->label,
                'new_label' => $this->request->input('data.label')
            ])
            ->setResponseData(null);
    }

    /**
     * Undo changing of node label
     * @return void
     * @throws Exception
     */
    public function undo(): void
    {
        // Validate
        $this->request->validate([
            'node_id' => 'required|integer',
            'original_label' => 'nullable|string'
        ]);
        $nodeId = $this->request->input('node_id');
        $originalLabel = $this->request->input('original_label');

        $nodeRepository = new NodeRepository();
        $node = $nodeRepository->getById($nodeId);

        // Define Schema
        $this->setSchemaId($node->schema_id);

        // Execute
        $nodeOptionsRepository = new NodeOptionsRepository();
        $nodeOptions = $nodeOptionsRepository->getByNodeId($nodeId);
        $dto = new NodeOptionsDto(
            nodeId: $nodeOptions->node_id,
            zIndex: $nodeOptions->z_index,
            parameterCode: $nodeOptions->parameter_code,
            hardwareCode: $nodeOptions->hardware_code,
            parentId: $nodeOptions->parent_id,
            label: $originalLabel,
        );
        $nodeOptionsRepository->update($nodeOptions, $dto);
    }
}
