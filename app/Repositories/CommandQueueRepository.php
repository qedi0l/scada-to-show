<?php

namespace App\Repositories;

use App\DTO\CommandQueueDto;
use App\DTO\Interfaces\DtoInterface;
use App\Models\CommandQueue;
use App\Repositories\Interfaces\EntityRepository;
use Exception;
use Illuminate\Database\Eloquent\Model;

/**
 * Command Queue Repository
 *
 * @method CommandQueue getById(int $itemId)
 * @method CommandQueue store(DtoInterface $dto)
 */
class CommandQueueRepository extends BaseRepository implements EntityRepository
{
    protected string $className = CommandQueue::class;

    public function update(CommandQueue|Model $item, CommandQueueDto|DtoInterface $dto): CommandQueue|Model
    {
        $item->receiver_title = $dto->receiverTitle;
        $item->command_title = $dto->commandTitle;
        $item->command_json = $dto->commandArray;
        $item->schema_id = $dto->schemaId;

        $item->save();

        return $item;
    }

    /**
     * @param int $schemaId
     * @return CommandQueue|Model|null
     * @throws Exception
     */
    public function getLatestBySchemaId(int $schemaId): CommandQueue|Model|null
    {
        $schema = (new SchemaRepository())->getById($schemaId);

        return $schema->commands()->latest()->first();
    }

    /**
     * @param string $schemaName
     * @return CommandQueue|Model|null
     */
    public function getLatestBySchemaName(string $schemaName): CommandQueue|Model|null
    {
        $schema = (new SchemaRepository())->getByName($schemaName);

        return $schema->commands()->latest()->first();
    }
}
