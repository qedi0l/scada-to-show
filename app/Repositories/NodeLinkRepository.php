<?php

namespace App\Repositories;

use App\DTO\Interfaces\DtoInterface;
use App\DTO\NodeLinkDto;
use App\Models\MnemoSchemaLine;
use App\Models\MnemoSchemaNodeLink;
use App\Repositories\Filters\FilterInterface;
use App\Repositories\Filters\NodeLinkFilter;
use App\Repositories\Interfaces\EntityRepository;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Node Link Repository
 *
 * @method MnemoSchemaNodeLink getById(int $itemId)
 * @method MnemoSchemaNodeLink store(DtoInterface $dto)
 */
class NodeLinkRepository extends BaseRepository implements EntityRepository
{
    protected string $className = MnemoSchemaNodeLink::class;

    /**
     * Index
     *
     * @param NodeLinkFilter|FilterInterface|null $filter
     * @return array|Collection
     */
    public function index(NodeLinkFilter|FilterInterface|null $filter = null): array|Collection
    {
        return MnemoSchemaLine::query()
            ->when(
                $filter,
                function (Builder $query, NodeLinkFilter $filter) {
                    $query
                        ->when(
                            $filter->nodeId,
                            fn(Builder $query, int $nodeId) => $query->where('node_id', $nodeId)
                        )
                        ->when(
                            $filter->schemaId,
                            fn(Builder $query, int $schemaId) => $query->where('schema_id', $schemaId)
                        );
                }
            )
            ->get();
    }

    /**
     * Update
     *
     * @param MnemoSchemaNodeLink|Model $item
     * @param NodeLinkDto|DtoInterface $dto
     * @return MnemoSchemaLine|Model
     * @throws Exception
     */
    public function update(MnemoSchemaNodeLink|Model $item, NodeLinkDto|DtoInterface $dto): MnemoSchemaNodeLink|Model
    {
        $item->node_id = $dto->nodeId;
        $item->schema_id = $dto->schemaId;

        $item->save();

        return $item;
    }

    /**
     * Get By Node ID
     *
     * @param int $nodeId
     * @return MnemoSchemaNodeLink|Model
     */
    public function getByNodeId(int $nodeId): MnemoSchemaNodeLink|Model
    {
        return MnemoSchemaNodeLink::query()->where('node_id', $nodeId)->firstOrFail();
    }
}
