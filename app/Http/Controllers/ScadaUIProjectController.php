<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProjectResource;
use App\Repositories\ProjectRepository;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ScadaUIProjectController extends Controller
{
    protected ProjectRepository $projectRepository;

    public function __construct()
    {
        $this->projectRepository = new ProjectRepository();
    }

    /**
     * Index
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function index(): AnonymousResourceCollection
    {
        $items = $this->projectRepository->index();

        return ProjectResource::collection($items);
    }
}
