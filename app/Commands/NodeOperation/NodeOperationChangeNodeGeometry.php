<?php

namespace App\Commands\NodeOperation;

use App\Commands\AbstractCommand;
use App\DTO\NodeGeometryDto;
use App\Receivers\NodeOperation\ChangeNodeGeometryReceiver;
use App\Repositories\NodeGeometryRepository;
use App\Repositories\NodeRepository;
use Exception;

class NodeOperationChangeNodeGeometry extends AbstractCommand
{
    /**
     * Change node geometry
     * @return void
     * @throws Exception
     */
    public function execute(): void
    {
        // Validate
        $this->request->validate([
            'data.x' => 'integer',
            'data.y' => 'integer',
            'data.rotate' => 'integer|between:0,359',
            'data.node_id' => 'required|integer',
        ]);
        $data = $this->request->input('data');
        $nodeGeometryRepository = new NodeGeometryRepository();
        $nodeGeometry = $nodeGeometryRepository->getByNodeId($data['node_id'])->load('node');
        $originalGeometry = [
            'x' => $nodeGeometry->x,
            'y' => $nodeGeometry->y,
            'rotate' => $nodeGeometry->rotation
        ];

        // Define Schema
        $nodeRepository = new NodeRepository();
        $node = $nodeRepository->getById($data['node_id']);
        $this->setSchemaId($node->schema_id);

        // Execute
        $receiver = new ChangeNodeGeometryReceiver();
        $receiver->changeNodeGeometry($this->request->toArray());

        // Set Changes and Response Data
        $this
            ->setChanges([
                'node_id' => $data['node_id'],
                'original_geometry' => $originalGeometry,
                'new_geometry' => $data
            ])
            ->setResponseData(null);
    }

    /**
     * Undo changing of node geometry
     * @throws Exception
     */
    public function undo(): void
    {
        // Validate
        $this->request->validate([
            'node_id' => 'required|integer',
            'original_geometry' => 'required|array',
            'new_geometry' => 'required|array',
        ]);
        $nodeId = $this->request->input('node_id');


        // Define Schema
        $nodeRepository = new NodeRepository();
        $node = $nodeRepository->getById($nodeId);
        $this->setSchemaId($node->schema_id);

        // Execute
        $nodeGeometryRepository = new NodeGeometryRepository();
        $originalGeometry = $this->request->input('original_geometry');
        $nodeGeometry = $nodeGeometryRepository->getByNodeId($nodeId);
        $dto = new NodeGeometryDto(
            nodeId: $nodeId,
            x: $originalGeometry['x'],
            y: $originalGeometry['y'],
            rotation: $originalGeometry['rotate'],
        );
        $nodeGeometryRepository->update($nodeGeometry, $dto);
    }
}
