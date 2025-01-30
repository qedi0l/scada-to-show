<?php

namespace App\Http\Controllers;

use App\Http\Resources\Lines\LineTypeResource;
use App\Repositories\LineTypeRepository;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ScadaUILineTypeController extends Controller
{
    public LineTypeRepository $repository;

    public function __construct()
    {
        $this->repository = new LineTypeRepository();
    }

    /**
     * Index
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function index(): AnonymousResourceCollection
    {
        $items = $this->repository->index();

        return LineTypeResource::collection($items);
    }
}
