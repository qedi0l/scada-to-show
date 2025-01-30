<?php

namespace App\Contracts;

use Illuminate\Http\JsonResponse;

interface IScadaUIProject
{
    /**
     * Get available projects
     * @return JsonResponse
     */
    public function getProjects(): JsonResponse;
}
