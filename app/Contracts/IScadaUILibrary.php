<?php

namespace App\Contracts;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

interface IScadaUILibrary
{
    /**
     * Get node library
     * @param Request $request
     * @return JsonResponse
     */
    public function getLibrary(Request $request): JsonResponse;

    /**
     * Get hardware hierarchy
     * @param Request $request
     * @return JsonResponse
     */
    public function getHierarchyLibrary(Request $request): JsonResponse;
}
