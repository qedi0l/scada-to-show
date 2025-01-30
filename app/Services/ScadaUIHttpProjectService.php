<?php

namespace App\Services;

use App\Contracts\IScadaUIProject;
use Http;
use Illuminate\Http\JsonResponse;

class ScadaUIHttpProjectService implements IScadaUIProject
{
    /**
     * @return JsonResponse
     */
    public function getProjects(): JsonResponse
    {
        $metricCatalogHost = config('app.metric_catalog_host');

        $scadaProjectsRoute = config('app.scada_project_route');

        $projects = Http::get($metricCatalogHost . $scadaProjectsRoute);

        return response()->json($projects);
    }
}
