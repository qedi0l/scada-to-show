<?php

namespace Tests\Feature\Repositories;

use App\DTO\Interfaces\DtoInterface;
use App\DTO\NodeDto;
use App\Models\MnemoSchemaNode;
use App\Repositories\NodeRepository;
use Tests\Feature\Repositories\Interfaces\RepositoryTestsInterface;
use Tests\Feature\Repositories\Traits\RepositoryMethods;
use Tests\TestCase;

/**
 * Tests of Node Repository
 */
class NodeRepositoryTest extends TestCase implements RepositoryTestsInterface
{
    use RepositoryMethods;

    public string $modelClass = MnemoSchemaNode::class;

    public string $repositoryClass = NodeRepository::class;

    public function getDto(): DtoInterface
    {
        /** @var MnemoSchemaNode $item */
        $item = $this->getFactory()->create();

        $dto = new NodeDto(
            title: $item->title,
            schemaId: $item->schema_id,
            groupId: $item->group_id,
            typeId: $item->type_id,
        );

        $item->delete();

        return $dto;
    }
}
