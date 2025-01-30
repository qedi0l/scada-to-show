<?php

namespace Tests\Feature\Repositories;

use App\DTO\Interfaces\DtoInterface;
use App\DTO\LineDto;
use App\Models\MnemoSchemaLine;
use App\Repositories\LineRepository;
use Tests\Feature\Repositories\Interfaces\RepositoryTestsInterface;
use Tests\Feature\Repositories\Traits\RepositoryMethods;
use Tests\TestCase;

/**
 * Tests of Line Repository
 */
class LineRepositoryTest extends TestCase implements RepositoryTestsInterface
{
    use RepositoryMethods;

    public string $modelClass = MnemoSchemaLine::class;

    public string $repositoryClass = LineRepository::class;

    public function getDto(): DtoInterface
    {
        /** @var MnemoSchemaLine $item */
        $item = $this->getFactory()->create();

        $dto = new LineDto(
            schemaId: $item->schema_id,
            firstNodeId: $item->first_node,
            secondNodeId: $item->second_node,
            sourcePosition: $item->source_position,
            targetPosition: $item->target_position,
        );

        $item->delete();

        return $dto;
    }
}
