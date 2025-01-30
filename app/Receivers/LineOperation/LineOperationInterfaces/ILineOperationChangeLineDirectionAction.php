<?php

namespace App\Receivers\LineOperation\LineOperationInterfaces;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

interface ILineOperationChangeLineDirectionAction extends ILineOperationAction
{
    /**
     * Changes line direction
     * @param array $request
     * @return JsonResponse
     */
    public function changeLineDirection(array $request): JsonResponse;
}
