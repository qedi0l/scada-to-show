<?php

namespace App\Http\Controllers;

use App\Http\Resources\Lines\LineArrowTypeResource;
use App\Repositories\LineArrowTypeRepository;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ScadaUILineArrowTypeController
{
    public LineArrowTypeRepository $repository;

    public function __construct()
    {
        $this->repository = new LineArrowTypeRepository();
    }

    /**
     * Index
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function index(): AnonymousResourceCollection
    {
        $items = $this->repository->index();

        return LineArrowTypeResource::collection($items);
    }
}
