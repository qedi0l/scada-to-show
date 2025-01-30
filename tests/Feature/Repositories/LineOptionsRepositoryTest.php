<?php

namespace Tests\Feature\Repositories;

use App\DTO\Interfaces\DtoInterface;
use App\DTO\LineOptionsDto;
use App\Models\MnemoSchemaLineOptions;
use App\Repositories\LineOptionsRepository;
use Tests\Feature\Repositories\Interfaces\RepositoryTestsInterface;
use Tests\Feature\Repositories\Traits\RepositoryMethods;
use Tests\TestCase;

/**
 * Tests of Line Options Repository
 */
class LineOptionsRepositoryTest extends TestCase implements RepositoryTestsInterface
{
    use RepositoryMethods;

    public string $modelClass = MnemoSchemaLineOptions::class;

    public string $repositoryClass = LineOptionsRepository::class;

    public function getDto(): DtoInterface
    {
        /** @var MnemoSchemaLineOptions $item */
        $item = $this->getFactory()->create();

        $dto = new LineOptionsDto(
            lineId: $item->line_id,
            text: $item->text,
            typeId: $item->type_id,
            firstArrow: $item->first_arrow,
            secondArrow: $item->second_arrow,
        );

        $item->delete();

        return $dto;
    }
}
