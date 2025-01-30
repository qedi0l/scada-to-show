<?php

namespace Tests\Feature\Repositories;

use App\DTO\Interfaces\DtoInterface;
use App\DTO\NodeGeometryDto;
use App\Models\MnemoSchemaNodeGeometry;
use App\Repositories\NodeGeometryRepository;
use Tests\Feature\Repositories\Interfaces\RepositoryTestsInterface;
use Tests\Feature\Repositories\Traits\RepositoryMethods;
use Tests\TestCase;

/**
 * Tests of Node Geometry Repository
 */
class NodeGeometryRepositoryTest extends TestCase implements RepositoryTestsInterface
{
    use RepositoryMethods;

    public string $modelClass = MnemoSchemaNodeGeometry::class;

    public string $repositoryClass = NodeGeometryRepository::class;

    public function getDto(): DtoInterface
    {
        /** @var MnemoSchemaNodeGeometry $item */
        $item = $this->getFactory()->create();

        $dto = new NodeGeometryDto(
            nodeId: $item->node_id,
            x: $item->x,
            y: $item->y,
            rotation: $item->rotation,
        );

        $item->delete();

        return $dto;
    }
}
