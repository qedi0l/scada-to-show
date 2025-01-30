<?php

namespace Tests\Feature\Repositories;

use App\DTO\Interfaces\DtoInterface;
use App\DTO\NodeTypeDto;
use App\Models\MnemoSchemaNodeType;
use App\Repositories\NodeTypeRepository;
use Tests\Feature\Repositories\Interfaces\RepositoryTestsInterface;
use Tests\Feature\Repositories\Traits\RepositoryMethods;
use Tests\TestCase;

/**
 * Tests of Node Type Repository
 */
class NodeTypeRepositoryTest extends TestCase implements RepositoryTestsInterface
{
    use RepositoryMethods;

    public string $modelClass = MnemoSchemaNodeType::class;

    public string $repositoryClass = NodeTypeRepository::class;

    public function getDto(): DtoInterface
    {
        /** @var MnemoSchemaNodeType $item */
        $item = $this->getFactory()->create();

        $dto = new NodeTypeDto(
            type: $item->type,
            hardwareType: $item->hardware_type,
            svg: $item->svg,
            nodeTypeGroupId: $item->node_type_group_id,
            title: $item->title,
            serviceType: $item->service_type,
        );

        $item->delete();

//        dd($item, $dto);

        return $dto;
    }
}
