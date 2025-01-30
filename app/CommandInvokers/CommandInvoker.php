<?php

namespace App\CommandInvokers;


use App\Commands\CommandInterface;
use App\DTO\CommandQueueDto;
use App\Enums\CommandType;
use App\Repositories\CommandQueueRepository;
use Exception;

/**
 * Command Invoker
 */
class CommandInvoker
{
    private CommandInterface $command;

    /**
     * Set Command
     *
     * @param CommandInterface $command
     * @return void
     */
    public function setCommand(CommandInterface $command): void
    {
        $this->command = $command;
    }

    /**
     * Run Command
     *
     * @throws Exception
     */
    public function run(): void
    {
        $this->command->execute();

        $this->writeCommandQueue();
    }

    /**
     * Undo Command
     *
     * @throws Exception
     */
    public function undo(): void
    {
        $this->command->undo();

        $this->clearCommandQueue();
    }

    /**
     * Get Executed Command Results
     *
     * @return mixed
     */
    public function getResponseData(): mixed
    {
        return $this->command->getResponseData();
    }

    /**
     * Write Data about Executed Command to Queue
     *
     * @return void
     * @throws Exception
     */
    private function writeCommandQueue(): void
    {
        if ($this->command->getChanges()) {
            $commandQueueRepository = new CommandQueueRepository();
            $dto = new CommandQueueDto(
                receiverTitle: '-',
                commandTitle: CommandType::getTypeByCommand($this->command)?->value,
                commandArray: $this->command->getChanges(),
                schemaId: $this->command->getSchemaId(),
            );
            $commandQueueRepository->store($dto);
        }
    }

    /**
     * Clear Data about Executed Command From Queue
     *
     * @return void
     * @throws Exception
     */
    private function clearCommandQueue(): void
    {
        if ($this->command->getSchemaId()) {
            $commandQueueRepository = new CommandQueueRepository();
            $lastCommandEntry = $commandQueueRepository->getLatestBySchemaId($this->command->getSchemaId());
            $commandQueueRepository->destroy($lastCommandEntry->getKey());
        }
    }
}
