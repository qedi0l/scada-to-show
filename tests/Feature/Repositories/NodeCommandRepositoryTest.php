<?php

namespace Tests\Feature\Repositories;

use App\DTO\Interfaces\DtoInterface;
use App\DTO\NodeCommandDto;
use App\Models\MnemoSchemaNodeCommand;
use App\Repositories\NodeCommandRepository;
use Tests\Feature\Repositories\Interfaces\RepositoryTestsInterface;
use Tests\Feature\Repositories\Traits\RepositoryMethods;
use Tests\TestCase;

/**
 * Tests of Node Command Repository
 */
class NodeCommandRepositoryTest extends TestCase implements RepositoryTestsInterface
{
    use RepositoryMethods;

    public string $modelClass = MnemoSchemaNodeCommand::class;

    public string $repositoryClass = NodeCommandRepository::class;

    public function getDto(): DtoInterface
    {
        /** @var MnemoSchemaNodeCommand $item */
        $item = $this->getFactory()->create();

        $dto = new NodeCommandDto(
            nodeId: $item->node_id,
            parameterCode: $item->parameter_code,
        );

        $item->delete();

        return $dto;
    }
}
