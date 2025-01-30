<?php

namespace App\Receivers\NodeOperation;

use App\DTO\NodeCommandDto;
use App\Exceptions\Receivers\CommandAlreadyInstalledToNodeException;
use App\Exceptions\Receivers\CommandNotAvailableForUsageInNodeException;
use App\Models\MnemoSchemaNodeCommand;
use App\Receivers\NodeOperation\NodeOperationInterfaces\INodeOperationAddCommandToNodeAction;
use App\Repositories\Filters\NodeCommandFilter;
use App\Repositories\NodeCommandRepository;
use App\Repositories\NodeOptionsRepository;
use App\Services\CatalogServices\CatalogSignalService;
use App\Services\CatalogServices\Models\Signal;
use Exception;
use Illuminate\Support\Collection;

class AddCommandToNodeReceiver implements INodeOperationAddCommandToNodeAction
{
    protected NodeCommandRepository $repository;
    protected NodeOptionsRepository $nodeOptionsRepository;

    public function __construct()
    {
        $this->repository = new NodeCommandRepository();
        $this->nodeOptionsRepository = new NodeOptionsRepository();
    }

    /**
     * @param array $request
     * @return MnemoSchemaNodeCommand
     * @throws CommandAlreadyInstalledToNodeException|CommandNotAvailableForUsageInNodeException
     * @throws Exception
     */
    public function addCommandToNode(array $request): MnemoSchemaNodeCommand
    {
        $requestData = $request['data'];
        $nodeId = $requestData['node_id'];
        $parameterCode = $requestData['parameter_code'];

        if ($this->isCommandInstalled($nodeId, $parameterCode)) {
            throw new CommandAlreadyInstalledToNodeException();
        }

        if ($this->isCommandNotAvailable($nodeId, $parameterCode)) {
            throw new CommandNotAvailableForUsageInNodeException();
        }

        $dto = new NodeCommandDto($nodeId, $parameterCode);

        return $this->repository->store($dto);
    }

    /**
     * Check if command is installed
     * @param int $nodeId
     * @param int $parameterCode
     * @return bool
     */
    private function isCommandInstalled(int $nodeId, int $parameterCode): bool
    {
        $filter = (new NodeCommandFilter())
            ->setNodeId($nodeId)
            ->setParameterCode($parameterCode);

        return $this->repository->index($filter)->isNotEmpty();
    }

    /**
     * Check if command is available
     * @param int $nodeId
     * @param int $parameterCode
     * @return bool
     */
    private function isCommandNotAvailable(int $nodeId, int $parameterCode): bool
    {
        $commands = $this->getCatalogCommands($nodeId);

        return $commands && $commands
                ->map(fn(Signal $signal) => $signal->transportSignalId)
                ->doesntContain($parameterCode);
    }

    /**
     * Get commands from Metric Reference Catalog
     * @param int $nodeId
     * @return Collection|null
     */
    public function getCatalogCommands(int $nodeId): ?Collection
    {
        $nodeOptions = $this->nodeOptionsRepository->getByNodeId($nodeId);

        $service = new CatalogSignalService();

        return $service->getReadableSignalsByHardwareCode($nodeOptions->hardware_code);
    }
}
