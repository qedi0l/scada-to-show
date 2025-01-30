<?php

namespace Tests\Feature\Repositories;

use App\DTO\Interfaces\DtoInterface;
use App\DTO\NodeTypeGroupDto;
use App\Models\MnemoSchemaNodeTypeGroup;
use App\Repositories\NodeTypeGroupRepository;
use Tests\Feature\Repositories\Interfaces\RepositoryTestsInterface;
use Tests\Feature\Repositories\Traits\RepositoryMethods;
use Tests\TestCase;

/**
 * Tests of Node Type Group Repository
 */
class NodeTypeGroupRepositoryTest extends TestCase implements RepositoryTestsInterface
{
    use RepositoryMethods;

    public string $modelClass = MnemoSchemaNodeTypeGroup::class;

    public string $repositoryClass = NodeTypeGroupRepository::class;

    public function getDto(): DtoInterface
    {
        /** @var MnemoSchemaNodeTypeGroup $item */
        $item = $this->getFactory()->create();

        $dto = new NodeTypeGroupDto(
            title: $item->title,
            description: $item->description,
            shortTitle: $item->short_title,
        );

        $item->delete();

        return $dto;
    }
}
