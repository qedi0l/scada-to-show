<?php

namespace App\Services;

use App\Contracts\IScadaUIProject;
use App\Models\MnemoSchemaProject;
use Illuminate\Http\JsonResponse;

class ScadaUIDBProjectService implements IScadaUIProject
{
    /**
     * @return JsonResponse
     */
    public function getProjects(): JsonResponse
    {
        $projects = MnemoSchemaProject::all()->toArray();

        return response()->json($projects);
    }
}
