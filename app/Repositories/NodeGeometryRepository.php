<?php

namespace App\Repositories;

use App\DTO\Interfaces\DtoInterface;
use App\DTO\NodeGeometryDto;
use App\Models\MnemoSchemaNodeGeometry;
use App\Repositories\Interfaces\EntityRepository;
use Illuminate\Database\Eloquent\Model;

/**
 * Node Geometry Repository
 *
 * @method MnemoSchemaNodeGeometry getById(int $itemId)
 * @method MnemoSchemaNodeGeometry store(DtoInterface $dto)
 */
class NodeGeometryRepository extends BaseRepository implements EntityRepository
{
    protected string $className = MnemoSchemaNodeGeometry::class;

    /**
     * Get By Node ID
     *
     * @param int $nodeId
     * @return MnemoSchemaNodeGeometry
     */
    public function getByNodeId(int $nodeId): MnemoSchemaNodeGeometry
    {
        return MnemoSchemaNodeGeometry::query()
            ->where('node_id', $nodeId)
            ->firstOrFail();
    }

    /**
     * Update
     *
     * @param MnemoSchemaNodeGeometry|Model $item
     * @param NodeGeometryDto|DtoInterface $dto
     * @return MnemoSchemaNodeGeometry|Model
     */
    public function update(MnemoSchemaNodeGeometry|Model $item, NodeGeometryDto|DtoInterface $dto): MnemoSchemaNodeGeometry|Model
    {
        $item->node_id = $dto->nodeId ?? $item->node_id;
        $item->x = $dto->x ?? $item->x;
        $item->y = $dto->y ?? $item->y;
        $item->rotation = $dto->rotation ?? $item->rotation;

        $item->save();

        return $item;
    }


}
