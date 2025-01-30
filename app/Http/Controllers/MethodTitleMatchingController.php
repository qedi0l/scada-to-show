<?php

namespace App\Http\Controllers;

use App\Http\Resources\MethodTitleMatchingNameResource;
use App\Repositories\MethodTitleMatchingRepository;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MethodTitleMatchingController extends Controller
{
    public MethodTitleMatchingRepository $repository;

    public function __construct()
    {
        $this->repository = new MethodTitleMatchingRepository();
    }

    /**
     * Get names of commands
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function names(): AnonymousResourceCollection
    {
        $items = $this->repository->index();

        return MethodTitleMatchingNameResource::collection($items);
    }
}
