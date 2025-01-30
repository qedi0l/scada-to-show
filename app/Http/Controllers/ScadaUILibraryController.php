<?php

namespace App\Http\Controllers;

use App\Contracts\IScadaUILibrary;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;

class ScadaUILibraryController extends Controller
{
    private IScadaUILibrary $libraryService;

    public function __construct()
    {
        $this->libraryService = App::make(IScadaUILibrary::class);
    }

    /**
     * Get library
     * @param Request $request
     * @return JsonResponse
     */
    public function getLibrary(Request $request): JsonResponse
    {
        return $this->libraryService->getHierarchyLibrary($request);
    }
}
