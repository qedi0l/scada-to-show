<?php

namespace Tests\Feature\Repositories;

use App\DTO\Interfaces\DtoInterface;
use App\DTO\ProjectDto;
use App\Models\MnemoSchemaProject;
use App\Repositories\ProjectRepository;
use Tests\Feature\Repositories\Interfaces\RepositoryTestsInterface;
use Tests\Feature\Repositories\Traits\RepositoryMethods;
use Tests\TestCase;

/**
 * Tests of Project Repository
 */
class ProjectRepositoryTest extends TestCase implements RepositoryTestsInterface
{
    use RepositoryMethods;

    public string $modelClass = MnemoSchemaProject::class;

    public string $repositoryClass = ProjectRepository::class;

    public function getDto(): DtoInterface
    {
        /** @var MnemoSchemaProject $item */
        $item = $this->getFactory()->create();

        $dto = new ProjectDto(
            title: $item->title,
            shortTitle: $item->short_title,
            description: $item->description,
        );

        $item->delete();

        return $dto;
    }
}
