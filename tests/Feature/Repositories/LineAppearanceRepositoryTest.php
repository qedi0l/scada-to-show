<?php

namespace Tests\Feature\Repositories;

use App\DTO\Interfaces\DtoInterface;
use App\DTO\LineAppearanceDto;
use App\Models\MnemoSchemaLineAppearance;
use App\Repositories\LineAppearanceRepository;
use Tests\Feature\Repositories\Interfaces\RepositoryTestsInterface;
use Tests\Feature\Repositories\Traits\RepositoryMethods;
use Tests\TestCase;

/**
 * Tests of Line Appearance Repository
 */
class LineAppearanceRepositoryTest extends TestCase implements RepositoryTestsInterface
{
    use RepositoryMethods;

    public string $modelClass = MnemoSchemaLineAppearance::class;

    public string $repositoryClass = LineAppearanceRepository::class;

    public function getDto(): DtoInterface
    {
        /** @var MnemoSchemaLineAppearance $item */
        $item = $this->getFactory()->create();

        $dto = new LineAppearanceDTO(
            lineId: $item->line_id,
            color: $item->color,
            opacity: $item->opacity,
            width: $item->width,
        );

        $item->delete();

        return $dto;
    }
}
