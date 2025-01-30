<?php

namespace Tests\Feature\Repositories;

use App\DTO\Interfaces\DtoInterface;
use App\DTO\NodeLinkDto;
use App\Models\MnemoSchemaNodeLink;
use App\Repositories\NodeLinkRepository;
use Tests\Feature\Repositories\Interfaces\RepositoryTestsInterface;
use Tests\Feature\Repositories\Traits\RepositoryMethods;
use Tests\TestCase;

/**
 * Tests of Node Link Repository
 */
class NodeLinkRepositoryTest extends TestCase implements RepositoryTestsInterface
{
    use RepositoryMethods;

    public string $modelClass = MnemoSchemaNodeLink::class;

    public string $repositoryClass = NodeLinkRepository::class;

    public function getDto(): DtoInterface
    {
        /** @var MnemoSchemaNodeLink $item */
        $item = $this->getFactory()->create();

        $dto = new NodeLinkDto(
            nodeId: $item->node_id,
            schemaId: $item->schema_id,
        );

        $item->delete();

        return $dto;
    }
}
