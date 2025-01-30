<?php

namespace Tests\Feature\Repositories;

use App\DTO\Interfaces\DtoInterface;
use App\DTO\NodeGroupDto;
use App\Models\MnemoSchemaNodeGroup;
use App\Repositories\NodeGroupRepository;
use Tests\Feature\Repositories\Interfaces\RepositoryTestsInterface;
use Tests\Feature\Repositories\Traits\RepositoryMethods;
use Tests\TestCase;

/**
 * Tests of Node Group Repository
 */
class NodeGroupRepositoryTest extends TestCase implements RepositoryTestsInterface
{
    use RepositoryMethods;

    public string $modelClass = MnemoSchemaNodeGroup::class;

    public string $repositoryClass = NodeGroupRepository::class;

    public function getDto(): DtoInterface
    {
        /** @var MnemoSchemaNodeGroup $item */
        $item = $this->getFactory()->create();

        $dto = new NodeGroupDto(
            title: $item->title,
            svgUrl: $item->svg_url,
        );

        $item->delete();

        return $dto;
    }
}
