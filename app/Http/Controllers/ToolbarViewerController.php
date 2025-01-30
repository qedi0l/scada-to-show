<?php

namespace App\Http\Controllers;

use App\Contracts\IAvailableCommands;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;

class ToolbarViewerController
{
    private IAvailableCommands $availableCommandsService;

    public function __construct()
    {
        $this->availableCommandsService = App::make(IAvailableCommands::class);
    }

    /**
     * Get available commands for chosen node
     * @param int $nodeId
     * @return JsonResponse
     */
    public function availableCommands(int $nodeId): JsonResponse
    {
        return response()->json($this->availableCommandsService->getCommands($nodeId));
    }

    /**
     * Get all commands for chosen node
     * @param int $nodeId
     * @return JsonResponse
     */
    public function getAllCommands(int $nodeId): JsonResponse
    {
        return response()->json($this->availableCommandsService->getAvailableCommands($nodeId));
    }
}
