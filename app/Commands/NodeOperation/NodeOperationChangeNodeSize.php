<?php

namespace App\Commands\NodeOperation;

use App\Commands\AbstractCommand;
use App\DTO\NodeAppearanceDto;
use App\Receivers\NodeOperation\ChangeNodeSizeReceiver;
use App\Repositories\NodeAppearanceRepository;
use App\Repositories\NodeRepository;
use Exception;

class NodeOperationChangeNodeSize extends AbstractCommand
{
    /**
     * Change node size
     * @return void
     * @throws Exception
     */
    public function execute(): void
    {
        // Validate
        $this->request->validate([
            'data.node_id' => ['required', 'integer'],
            'data.height' => 'integer',
            'data.width' => 'integer'
        ]);
        $nodeId = $this->request->input('data.node_id');
        $nodeRepository = new NodeRepository();
        $node = $nodeRepository->getById($nodeId);
        $nodeAppearanceRepository = new NodeAppearanceRepository();
        $nodeAppearance = $nodeAppearanceRepository->getByNodeId($nodeId);

        // Define Schema
        $this->setSchemaId($node->schema_id);

        // Execute
        $receiver = new ChangeNodeSizeReceiver();
        $message = $receiver->changeNodeSize($this->request->toArray());

        // Set Changes and Response Data
        $this
            ->setChanges([
                'node_id' => $node->getKey(),
                'original_size' => $nodeAppearance->only(['width', 'height']),
                'new_size' => $this->request->input('data')
            ])
            ->setResponseData(['message' => $message]);
    }


    /**
     * Undo changing of node size
     * @return void
     * @throws Exception
     */
    public function undo(): void
    {
        // Validate
        $this->request->validate([
            'node_id' => ['required', 'integer'],
            'original_size' => ['required', 'array'],
            'original_size.width' => ['required', 'integer'],
            'original_size.height' => ['required', 'integer'],
            'new_size' => ['required', 'array'],
            'new_size.width' => 'integer',
            'new_size.height' => 'integer',
        ]);
        $nodeId = $this->request->input('node_id');
        $originalSize = $this->request->input('original_size');
        $nodeRepository = new NodeRepository();
        $node = $nodeRepository->getById($nodeId);
        $nodeAppearanceRepository = new NodeAppearanceRepository();
        $nodeAppearance = $nodeAppearanceRepository->getByNodeId($this->request->input('node_id'));

        // Define Schema
        $this->setSchemaId($node->schema_id);

        // Execute
        $dto = new NodeAppearanceDto(
            nodeId: $nodeAppearance->node_id,
            width: $originalSize['width'],
            height: $originalSize['height'],
            svgUrl: $nodeAppearance->svg_url,
            minSvg: $nodeAppearance->min_svg,
        );
        $nodeAppearanceRepository->update($nodeAppearance, $dto);
    }
}
