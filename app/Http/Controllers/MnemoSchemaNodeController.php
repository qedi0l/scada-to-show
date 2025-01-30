<?php

namespace App\Http\Controllers;

use App\Contracts\IScadaUINode;
use App\Http\Resources\Nodes\ChildNodesResource;
use App\Http\Resources\Nodes\NodeHierarchyResource;
use App\Repositories\NodeRepository;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\App;

class MnemoSchemaNodeController extends Controller
{
    private IScadaUINode $service;

    public NodeRepository $repository;

    public function __construct()
    {
        $this->service = App::make(IScadaUINode::class);
        $this->repository = new NodeRepository();
    }

    /**
     * Get hierarchy of schema
     * @param string $schemaName
     * @param $parentNodeId
     * @return mixed
     */
    public function showHierarchyBySchema(string $schemaName, $parentNodeId)
    {
        $items = $this->repository->getHierarchy($schemaName, $parentNodeId);

        return NodeHierarchyResource::collection($items)
            ->mapWithKeys(function (NodeHierarchyResource $item) {
                return [$item->getKey() => $item];
            });
    }

    /**
     * Get children of chosen node
     * @param int $nodeId
     * @return AnonymousResourceCollection
     */
    public function getChildNodes(int $nodeId): AnonymousResourceCollection
    {
        $items = $this->service->getChildNodes($nodeId);
        return ChildNodesResource::collection($items);
    }
}
