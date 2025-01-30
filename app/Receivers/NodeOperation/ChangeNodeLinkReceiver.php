<?php

namespace App\Receivers\NodeOperation;

use App\DTO\NodeLinkDto;
use App\Models\MnemoSchemaNodeLink;
use App\Receivers\NodeOperation\NodeOperationInterfaces\INodeOperationChangeNodeLinkAction;
use App\Repositories\NodeLinkRepository;
use App\Repositories\SchemaRepository;
use Exception;

class ChangeNodeLinkReceiver implements INodeOperationChangeNodeLinkAction
{
    protected NodeLinkRepository $nodeLinkRepository;
    protected SchemaRepository $schemaRepository;

    public function __construct()
    {
        $this->nodeLinkRepository = new NodeLinkRepository();
        $this->schemaRepository = new SchemaRepository();
    }

    /**
     * @param array $request
     * @return MnemoSchemaNodeLink
     * @throws Exception
     */
    public function changeNodeLink(array $request): MnemoSchemaNodeLink
    {
        $requestData = $request['data'];

        $schema = $this->schemaRepository->getByName($requestData['link']['schema_name']);
        $nodeLinkDto = new NodeLinkDTO(
            nodeId: $requestData['node_id'],
            schemaId: $schema->getKey()
        );

        try {
            $nodeLink = $this->nodeLinkRepository->getByNodeId($requestData['node_id']);
        } catch (Exception) {
            $nodeLink = null;
        }

        if (is_null($nodeLink)) {
            return $this->nodeLinkRepository->store($nodeLinkDto);
        } else {
            return $this->nodeLinkRepository->update($nodeLink, $nodeLinkDto);
        }
    }
}
