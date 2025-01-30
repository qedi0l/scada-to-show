<?php

namespace App\Receivers\NodeOperation;

use App\DTO\NodeGeometryDto;
use App\Models\MnemoSchemaNodeGeometry;
use App\Receivers\NodeOperation\NodeOperationInterfaces\INodeOperationChangeNodeGeometryAction;
use App\Repositories\NodeGeometryRepository;

class ChangeNodeGeometryReceiver implements INodeOperationChangeNodeGeometryAction
{
    protected NodeGeometryRepository $nodeGeometryRepository;

    public function __construct()
    {
        $this->nodeGeometryRepository = new NodeGeometryRepository();
    }

    /**
     * @param array $request
     * @return MnemoSchemaNodeGeometry
     */
    public function changeNodeGeometry(array $request): MnemoSchemaNodeGeometry
    {
        $data = $request['data'];

        // Update Node Geometry
        $nodeGeometry = $this->nodeGeometryRepository->getByNodeId($data['node_id']);
        $nodeGeometryDto = new NodeGeometryDto(
            nodeId: $data['node_id'],
            x: $data['x'] ?? null,
            y: $data['y'] ?? null,
            rotation: $data['rotate'] ?? null,
        );
        return $this->nodeGeometryRepository->update($nodeGeometry, $nodeGeometryDto);
    }
}
