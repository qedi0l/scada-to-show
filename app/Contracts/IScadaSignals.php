<?php

namespace App\Contracts;

use Illuminate\Http\JsonResponse;

interface IScadaSignals
{
    /**
     * Get available signals for node
     * @param int $nodeId
     * @return JsonResponse
     */
    public function getNodeAvailableSignals(int $nodeId): JsonResponse;

    /**
     * Get signal's meta data
     * @param int $nodeId
     * @return array
     */
    public function getSignalsMetaData(int $nodeId): array;

}
