<?php

namespace App\Repositories;

use App\DTO\Interfaces\DtoInterface;
use App\DTO\NodeAppearanceDto;
use App\Models\MnemoSchemaNodeAppearance;
use App\Repositories\Interfaces\EntityRepository;
use Illuminate\Database\Eloquent\Model;

/**
 * Node Appearance Repository
 *
 * @method MnemoSchemaNodeAppearance getById(int $itemId)
 * @method MnemoSchemaNodeAppearance store(DtoInterface $dto)
 */
class NodeAppearanceRepository extends BaseRepository implements EntityRepository
{
    protected string $className = MnemoSchemaNodeAppearance::class;

    /**
     * Get By Node ID
     *
     * @param int $nodeId
     * @return MnemoSchemaNodeAppearance|Model
     */
    public function getByNodeId(int $nodeId): MnemoSchemaNodeAppearance|Model
    {
        return MnemoSchemaNodeAppearance::query()
            ->where('node_id', $nodeId)
            ->firstOrFail();
    }

    /**
     * Update
     *
     * @param MnemoSchemaNodeAppearance|Model $item
     * @param NodeAppearanceDto|DtoInterface $dto
     * @return MnemoSchemaNodeAppearance|Model
     */
    public function update(
        MnemoSchemaNodeAppearance|Model $item,
        NodeAppearanceDto|DtoInterface $dto
    ): MnemoSchemaNodeAppearance|Model {
        $item->node_id = $dto->nodeId;
        $item->width = $dto->width;
        $item->height = $dto->height;
        $item->svg_url = $dto->svgUrl;
        $item->min_svg = $dto->minSvg;

        $item->save();

        return $item;
    }
}
