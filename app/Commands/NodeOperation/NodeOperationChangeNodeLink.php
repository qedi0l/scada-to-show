<?php

namespace App\Commands\NodeOperation;

use App\Commands\AbstractCommand;
use App\DTO\NodeLinkDto;
use App\Receivers\NodeOperation\ChangeNodeLinkReceiver;
use App\Repositories\NodeLinkRepository;
use App\Repositories\NodeRepository;
use Exception;

class NodeOperationChangeNodeLink extends AbstractCommand
{
    /**
     * Change node link
     * @return void
     * @throws Exception
     */
    public function execute(): void
    {
        // Validate
        $this->request->validate([
            'data.node_id' => 'required|integer',
            'data.link' => 'required|array',
            'data.link.schema_name' => 'required|string',
        ]);
        $nodeId = $this->request->input('data.node_id');
        $nodeRepository = new NodeRepository();
        $node = $nodeRepository->getById($nodeId);

        $nodeLinkRepository = new NodeLinkRepository();
        try {
            $nodeLinkSchemaId = $nodeLinkRepository->getByNodeId($node->getKey())?->schema_id;
        } catch (Exception) {
            $nodeLinkSchemaId = null;
        }

        // Define Schema
        $this->setSchemaId($node->schema_id);

        // Execute
        $receiver = new ChangeNodeLinkReceiver();
        $nodeLinkUpdated = $receiver->changeNodeLink($this->request->toArray());

        // Set Changes and Response Data
        $this
            ->setChanges([
                'node_id' => $node->getKey(),
                'original_link' => $nodeLinkSchemaId,
                'new_link' => $nodeLinkUpdated->schema_id
            ])
            ->setResponseData(['node_link' => $nodeLinkUpdated->toArray()]);
    }


    /**
     * Undo changing of node link
     * @return void
     * @throws Exception
     */
    public function undo(): void
    {
        // Validate
        $this->request->validate([
            'node_id' => 'required|integer',
            'original_link' => 'nullable|integer',
            'new_link' => 'required|integer',
        ]);

        // Define Schema
        $nodeRepository = new NodeRepository();
        $node = $nodeRepository->getById($this->request->input('node_id'));
        $this->setSchemaId($node->schema_id);

        // Execute
        $nodeLinkRepository = new NodeLinkRepository();
        $nodeLink = $nodeLinkRepository->getByNodeId($this->request->input('node_id'));

        $originalLink = $this->request->input('original_link');

        if (is_null($originalLink)) {
            $nodeLinkRepository->destroy($nodeLink);
        } else {
            $nodeLinkDto = new NodeLinkDto(
                nodeId: $nodeLink->node_id,
                schemaId: $originalLink
            );
            $nodeLinkRepository->update($nodeLink, $nodeLinkDto);
        }
    }
}
