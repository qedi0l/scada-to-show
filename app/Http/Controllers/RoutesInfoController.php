<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/**
 * Route Info Controller
 */
class RoutesInfoController extends Controller
{
    /**
     * Handle the incoming request.
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        $result = [];

        /** @var \Illuminate\Routing\Route $route */
        foreach (Route::getRoutes() as $route) {
            $result[] = [
                'route' => $route->uri(),
                'method' => $route->methods()[0],
            ];
        }

        return response()->json(['data' => $result]);
    }
}
