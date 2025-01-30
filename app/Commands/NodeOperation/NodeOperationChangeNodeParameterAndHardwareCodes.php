<?php

namespace App\Commands\NodeOperation;

use App\Commands\AbstractCommand;
use App\DTO\NodeOptionsDto;
use App\Receivers\NodeOperation\ChangeNodeParameterAndHardwareCodesReceiver;
use App\Repositories\NodeOptionsRepository;
use App\Repositories\NodeRepository;
use Exception;

class NodeOperationChangeNodeParameterAndHardwareCodes extends AbstractCommand
{
    /**
     * Change node parameter and hardware codes
     * @return void
     * @throws Exception
     */
    public function execute(): void
    {
        // Validate
        $this->request->validate([
            'data.node_id' => 'required|integer',
            'data.parameter_code' => 'nullable|integer',
            'data.hardware_code' => 'nullable|integer'
        ]);
        $nodeId = $this->request->input('data.node_id');
        $nodeRepository = new NodeRepository();
        $node = $nodeRepository->getById($nodeId)->load(['options']);

        // Define Schema
        $this->setSchemaId($node->schema_id);

        // Execute
        $receiver = new ChangeNodeParameterAndHardwareCodesReceiver();
        $message = $receiver->changeNodeParameterAndHardwareCodes($this->request->toArray());

        // Set Changes and Response Data
        $changes = [
            'node_id' => $nodeId,
            'original_hardware_code' => $node->options->hardware_code,
            'original_parameter_code' => $node->options->parameter_code,
        ];
        if ($this->request->has('data.hardware_code')) {
            $changes['new_hardware_code'] = $this->request->input('data.hardware_code');
        }
        if ($this->request->has('data.parameter_code')) {
            $changes['new_parameter_code'] = $this->request->input('data.parameter_code');
        }
        $this
            ->setChanges($changes)
            ->setResponseData(['message' => $message]);
    }

    /**
     * Undo changing on node parameter and hardware codes
     * @return void
     * @throws Exception
     */
    public function undo(): void
    {
        // Validate
        $this->request->validate([
            'node_id' => 'required|integer',
            'parameter_code' => 'nullable|integer',
            'hardware_code' => 'nullable|integer'
        ]);
        $nodeId = $this->request->input('node_id');

        $nodeRepository = new NodeRepository();
        $node = $nodeRepository->getById($nodeId);

        $nodeOptionsRepository = new NodeOptionsRepository();
        $nodeOptions = $nodeOptionsRepository->getByNodeId($nodeId);

        // Define Schema
        $this->setSchemaId($node->schema_id);

        // Execute
        $dto = new NodeOptionsDto(
            nodeId: $nodeOptions->node_id,
            zIndex: $nodeOptions->z_index,
            parameterCode: $this->request->input('original_parameter_code'),
            hardwareCode: $this->request->input('original_hardware_code'),
            parentId: $nodeOptions->parent_id,
            label: $nodeOptions->label,
        );
        $nodeOptionsRepository->update($nodeOptions, $dto);
    }
}
