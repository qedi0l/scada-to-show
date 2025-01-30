<?php

namespace App\Http\Controllers;

use App\Http\Requests\Nodes\NodeTypeStoreRequest;
use App\Http\Requests\Nodes\NodeTypeUpdateRequest;
use App\Http\Resources\Nodes\NodeTypeResource;
use App\Http\Resources\Nodes\NodeTypeSvgResource;
use App\Repositories\Filters\NodeTypeFilter;
use App\Repositories\NodeTypeRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Throwable;

/**
 * Node Type Controller
 */
class ScadaUiNodeTypeController extends Controller
{
    public NodeTypeRepository $repository;

    public function __construct()
    {
        $this->repository = new NodeTypeRepository();
    }

    /**
     * List
     *
     * @return mixed
     */
    public function index(): Collection
    {
        $filter = (new NodeTypeFilter())
            ->setSvgNotNull(true);

        $items = $this->repository->index($filter);

        return NodeTypeSvgResource::collection($items)
            ->mapWithKeys(function ($item) {
                return [$item->type => $item->svg];
            });
    }

    /**
     * Store
     *
     * @param NodeTypeStoreRequest $request
     * @return NodeTypeResource
     * @throws Exception
     */
    public function store(NodeTypeStoreRequest $request): NodeTypeResource
    {
        $item = $this->repository->store($request->dto());

        return NodeTypeResource::make($item);
    }

    /**
     * Update
     *
     * @param NodeTypeUpdateRequest $request
     * @return NodeTypeResource
     */
    public function update(NodeTypeUpdateRequest $request): NodeTypeResource
    {
        $item = $this->repository->update($request->nodeType, $request->dto());

        return NodeTypeResource::make($item);
    }

    /**
     * Destroy
     *
     * @param int $nodeTypeId
     * @return JsonResponse
     * @throws Throwable
     */
    public function destroy(int $nodeTypeId): JsonResponse
    {
        $result = $this->repository->destroy($nodeTypeId);

        return response()->json(['success' => $result]);
    }
}
