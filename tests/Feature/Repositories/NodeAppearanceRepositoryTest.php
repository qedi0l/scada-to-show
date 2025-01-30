<?php

namespace Tests\Feature\Repositories;

use App\DTO\Interfaces\DtoInterface;
use App\DTO\NodeAppearanceDto;
use App\Models\MnemoSchemaNodeAppearance;
use App\Repositories\NodeAppearanceRepository;
use Tests\Feature\Repositories\Interfaces\RepositoryTestsInterface;
use Tests\Feature\Repositories\Traits\RepositoryMethods;
use Tests\TestCase;

/**
 * Tests of Node Appearance Repository
 */
class NodeAppearanceRepositoryTest extends TestCase implements RepositoryTestsInterface
{
    use RepositoryMethods;

    public string $modelClass = MnemoSchemaNodeAppearance::class;

    public string $repositoryClass = NodeAppearanceRepository::class;

    public function getDto(): DtoInterface
    {
        /** @var MnemoSchemaNodeAppearance $item */
        $item = $this->getFactory()->create();

        $dto = new NodeAppearanceDto(
            nodeId: $item->node_id,
            width: $item->width,
            height: $item->height,
            svgUrl: $item->svg_url,
            minSvg: $item->min_svg,
        );

        $item->delete();

        return $dto;
    }
}
