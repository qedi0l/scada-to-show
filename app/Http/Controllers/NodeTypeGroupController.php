<?php

namespace App\Http\Controllers;

use App\Http\Requests\Nodes\NodeTypeGroupDestroyRequest;
use App\Http\Requests\Nodes\NodeTypeGroupStoreRequest;
use App\Http\Requests\Nodes\NodeTypeGroupUpdateRequest;
use App\Http\Requests\Nodes\NodeTypeIndexRequest;
use App\Http\Resources\Nodes\NodeTypeGroupResource;
use App\Repositories\Filters\NodeTypeGroupFilter;
use App\Repositories\NodeTypeGroupRepository;
use Exception;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Node Type Group Controller
 */
class NodeTypeGroupController
{
    public function __construct(public NodeTypeGroupRepository $repository)
    {
    }

    /**
     * Index
     *
     * @param NodeTypeIndexRequest $request
     * @return AnonymousResourceCollection
     */
    public function index(NodeTypeIndexRequest $request): AnonymousResourceCollection
    {
        $filter = new NodeTypeGroupFilter();
        if ($request->has('isServiceType')) {
            $filter->setHasServiceTypeNodes($request->boolean('isServiceType'));
        }

        $items = $this->repository->index($filter)
            ->load([
                'types' => function (HasMany $query) use ($filter) {
                    $query->when(
                        !is_null($filter->hasServiceTypeNodes),
                        function ($query) use ($filter) {
                            $query->where('service_type', $filter->hasServiceTypeNodes);
                        }
                    );
                }
            ]);

        return NodeTypeGroupResource::collection($items);
    }

    /**
     * Store
     *
     * @param NodeTypeGroupStoreRequest $request
     * @return NodeTypeGroupResource
     * @throws Exception
     */
    public function store(NodeTypeGroupStoreRequest $request): NodeTypeGroupResource
    {
        $item = $this->repository->store($request->dto());

        return NodeTypeGroupResource::make($item);
    }

    /**
     * Update
     *
     * @param NodeTypeGroupUpdateRequest $request
     * @return NodeTypeGroupResource
     * @throws Exception
     */
    public function update(NodeTypeGroupUpdateRequest $request): NodeTypeGroupResource
    {
        $nodeTypeGroup = $this->repository->getById($request->node_type_group_id);

        $nodeTypeGroup = $this->repository->update($nodeTypeGroup, $request->dto());

        return NodeTypeGroupResource::make($nodeTypeGroup);
    }

    /**
     * Destroy
     *
     * @param NodeTypeGroupDestroyRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(NodeTypeGroupDestroyRequest $request): JsonResponse
    {
        $nodeTypeGroup = $this->repository->getByTitle($request->group_title);

        $result = $this->repository->destroy($nodeTypeGroup);

        return response()->json(['success' => $result]);
    }
}
