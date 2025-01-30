<?php

namespace Tests\Feature\Repositories;

use App\DTO\Interfaces\DtoInterface;
use App\DTO\NodeOptionsDto;
use App\Models\MnemoSchemaNodeOptions;
use App\Repositories\NodeOptionsRepository;
use Tests\Feature\Repositories\Interfaces\RepositoryTestsInterface;
use Tests\Feature\Repositories\Traits\RepositoryMethods;
use Tests\TestCase;

/**
 * Tests of Node Options Repository
 */
class NodeOptionsRepositoryTest extends TestCase implements RepositoryTestsInterface
{
    use RepositoryMethods;

    public string $modelClass = MnemoSchemaNodeOptions::class;

    public string $repositoryClass = NodeOptionsRepository::class;

    public function getDto(): DtoInterface
    {
        /** @var MnemoSchemaNodeOptions $item */
        $item = $this->getFactory()->create();

        $dto = new NodeOptionsDto(
            nodeId: $item->node_id,
            zIndex: $item->z_index,
            parameterCode: $item->parameter_code,
            hardwareCode: $item->hardware_code,
            parentId: $item->parent_id,
            label: $item->label,
        );

        $item->delete();

        return $dto;
    }
}
