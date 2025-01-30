<?php

namespace App\Http\Controllers;

use App\CommandInvokers\CommandInvoker;
use App\Commands\CommandInterface;
use App\Enums\CommandType;
use App\Http\Requests\Commands\ExecuteCommandRequest;
use App\Http\Resources\Schemas\MnemoSchemaResource;
use App\Repositories\CommandQueueRepository;
use App\Repositories\SchemaRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GeneralEditorController
{
    protected CommandInvoker $invoker;

    public function __construct()
    {
        $this->invoker = new CommandInvoker();
    }


    /**
     * Execute received command
     * @param ExecuteCommandRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function execute(ExecuteCommandRequest $request): JsonResponse
    {
        // Define Command
        $command = $this->getCommandByRequest($request);
        if (is_null($command)) {
            return response()->json(['error' => 'Command not found'], 404);
        }

        // Execute
        $this->invoker->setCommand($command);
        $this->invoker->run();

        // Response
        return response()->json([
            'command' => $request->method_title,
            'status_code' => 200,
            'data' => $this->invoker->getResponseData(),
        ]);
    }

    /**
     * Undo Latest command
     * @param string $schemaName
     * @return JsonResponse
     * @throws Exception
     */
    public function undo(string $schemaName): JsonResponse
    {
        // Define Command
        $command = $this->getLatestSchemaCommandFromQueue($schemaName);
        if (is_null($command)) {
            return response()->json(['status' => 'error', 'message' => 'No command to undo.'], 404);
        }

        // Execute
        $this->invoker->setCommand($command);
        $this->invoker->undo();

        // Response
        $schema = (new SchemaRepository())->getSchemaDataByName($schemaName);
        return response()->json(['Schema' => MnemoSchemaResource::make($schema)]);
    }

    /**
     * Define Command By Request
     *
     * @param ExecuteCommandRequest $request
     * @return CommandInterface|null
     */
    private function getCommandByRequest(ExecuteCommandRequest $request): ?CommandInterface
    {
        $commandClass = CommandType::getCommandByTitle($request->method_title);

        if (is_null($commandClass)) {
            return null;
        }

        return new $commandClass($request);
    }

    /**
     * Latest executed Schema Command in Queue
     *
     * @param string $schemaName
     * @return CommandInterface|null
     */
    private function getLatestSchemaCommandFromQueue(string $schemaName): ?CommandInterface
    {
        $commandQueueRepository = new CommandQueueRepository();

        $lastCommandEntry = $commandQueueRepository->getLatestBySchemaName($schemaName);
        if (is_null($lastCommandEntry)) {
            return null;
        }

        $commandClass = CommandType::getCommandByTitle($lastCommandEntry->command_title);
        if (is_null($commandClass)) {
            return null;
        }

        $request = new Request($lastCommandEntry->command_json);

        return new $commandClass($request);
    }
}
