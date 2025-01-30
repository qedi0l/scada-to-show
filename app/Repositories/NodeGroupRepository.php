<?php

namespace App\Repositories;

use App\DTO\Interfaces\DtoInterface;
use App\DTO\NodeGroupDto;
use App\Models\MnemoSchemaNodeGroup;
use App\Models\MnemoSchemaNodeOptions;
use App\Repositories\Interfaces\EntityRepository;
use Illuminate\Database\Eloquent\Model;

/**
 * Node Group Repository
 *
 * @method MnemoSchemaNodeGroup getById(int $itemId)
 * @method MnemoSchemaNodeGroup store(DtoInterface $dto)
 */
class NodeGroupRepository extends BaseRepository implements EntityRepository
{
    protected string $className = MnemoSchemaNodeGroup::class;

    /**
     * Update
     *
     * @param MnemoSchemaNodeGroup|Model $item
     * @param NodeGroupDto|DtoInterface $dto
     * @return MnemoSchemaNodeOptions|Model
     */
    public function update(
        MnemoSchemaNodeGroup|Model $item,
        NodeGroupDto|DtoInterface $dto
    ): MnemoSchemaNodeOptions|Model {
        $item->title = $dto->title;
        $item->svg_url = $dto->svgUrl;

        $item->save();

        return $item;
    }
}
