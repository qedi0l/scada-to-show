<?php

namespace App\Http\Controllers;

use App\Http\Resources\Nodes\MnemoSchemaNodeTypeResource;
use App\Repositories\NodeTypeRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MnemoSchemaNodeTypeController extends Controller
{
    public NodeTypeRepository $repository;

    public function __construct()
    {
        $this->repository = new NodeTypeRepository();
    }

    /**
     * Index
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $items = $this->repository->index();

        return MnemoSchemaNodeTypeResource::collection($items);
    }
}
