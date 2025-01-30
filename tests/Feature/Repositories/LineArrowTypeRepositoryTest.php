<?php

namespace Tests\Feature\Repositories;

use App\DTO\Interfaces\DtoInterface;
use App\DTO\LineArrowTypeDto;
use App\Models\MnemoSchemaLineArrowType;
use App\Repositories\LineArrowTypeRepository;
use Tests\Feature\Repositories\Interfaces\RepositoryTestsInterface;
use Tests\Feature\Repositories\Traits\RepositoryMethods;
use Tests\TestCase;

/**
 * Tests of Line Arrow Type Repository
 */
class LineArrowTypeRepositoryTest extends TestCase implements RepositoryTestsInterface
{
    use RepositoryMethods;

    public string $modelClass = MnemoSchemaLineArrowType::class;

    public string $repositoryClass = LineArrowTypeRepository::class;

    public function getDto(): DtoInterface
    {
        /** @var MnemoSchemaLineArrowType $item */
        $item = $this->getFactory()->create();

        $dto = new LineArrowTypeDto(
            arrowTypeTitle: $item->arrow_type_title,
        );

        $item->delete();

        return $dto;
    }
}
