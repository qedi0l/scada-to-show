<?php

namespace App\Contracts;

use Illuminate\Http\JsonResponse;

interface IAvailableCommands
{
    /**
     * Get commands for node
     * @param int $nodeId
     * @return array|null
     */
    public function getCommands(int $nodeId): array|null;

    /**
     * Get available commands for node
     * @param int $nodeId
     * @return array|JsonResponse
     */
    public function getAvailableCommands(int $nodeId): array|JsonResponse;

}
