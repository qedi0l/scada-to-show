<?php

namespace Tests\Feature\Repositories;

use App\DTO\Interfaces\DtoInterface;
use App\DTO\SchemaDto;
use App\Models\MnemoSchema;
use App\Repositories\SchemaRepository;
use Tests\Feature\Repositories\Interfaces\RepositoryTestsInterface;
use Tests\Feature\Repositories\Traits\RepositoryMethods;
use Tests\TestCase;

/**
 * Tests of Schema Repository
 */
class SchemaRepositoryTest extends TestCase implements RepositoryTestsInterface
{
    use RepositoryMethods;

    public string $modelClass = MnemoSchema::class;

    public string $repositoryClass = SchemaRepository::class;

    public function getDto(): DtoInterface
    {
        /** @var MnemoSchema $item */
        $item = $this->getFactory()->create();

        $dto = new SchemaDto(
            name: $item->name,
            title: $item->title,
            projectId: $item->project_id,
            isActive: $item->is_active,
            default: $item->default,
        );

        $item->delete();

        return $dto;
    }
}
