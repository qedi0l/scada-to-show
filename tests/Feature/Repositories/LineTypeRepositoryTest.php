<?php

namespace Tests\Feature\Repositories;

use App\DTO\Interfaces\DtoInterface;
use App\DTO\LineTypeDto;
use App\Models\MnemoSchemaLineType;
use App\Repositories\LineTypeRepository;
use Tests\Feature\Repositories\Interfaces\RepositoryTestsInterface;
use Tests\Feature\Repositories\Traits\RepositoryMethods;
use Tests\TestCase;

/**
 * Tests of Line Type Repository
 */
class LineTypeRepositoryTest extends TestCase implements RepositoryTestsInterface
{
    use RepositoryMethods;

    public string $modelClass = MnemoSchemaLineType::class;

    public string $repositoryClass = LineTypeRepository::class;

    public function getDto(): DtoInterface
    {
        /** @var MnemoSchemaLineType $item */
        $item = $this->getFactory()->create();

        $dto = new LineTypeDto(
            type: $item->type,
        );

        $item->delete();

        return $dto;
    }
}
