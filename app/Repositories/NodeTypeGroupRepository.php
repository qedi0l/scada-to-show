<?php

namespace App\Repositories;

use App\DTO\Interfaces\DtoInterface;
use App\DTO\NodeTypeGroupDto;
use App\Models\MnemoSchemaNodeTypeGroup;
use App\Repositories\Filters\NodeTypeGroupFilter;
use App\Repositories\Interfaces\EntityRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Node Type Group Repository
 *
 * @method MnemoSchemaNodeTypeGroup getById(int $itemId)
 * @method MnemoSchemaNodeTypeGroup store(DtoInterface $dto)
 */
class NodeTypeGroupRepository extends BaseRepository implements EntityRepository
{
    protected string $className = MnemoSchemaNodeTypeGroup::class;

    /**
     * Index
     *
     * @param NodeTypeGroupFilter|Filters\FilterInterface|null $filter
     * @return Collection
     */
    public function index(NodeTypeGroupFilter|null|Filters\FilterInterface $filter = null): Collection
    {
        return MnemoSchemaNodeTypeGroup::query()
            ->when(
                $filter,
                function (Builder $query, NodeTypeGroupFilter $filter) {
                    $query
                        ->when(
                            !is_null($filter->hasServiceTypeNodes),
                            function (Builder $query) use ($filter) {
                                $query->whereHas(
                                    'types',
                                    fn(Builder $query) => $query->where('service_type', $filter->hasServiceTypeNodes)
                                );
                            }
                        );
                }
            )
            ->get();
    }

    /**
     * Get First By Title
     *
     * @param string $title
     * @return MnemoSchemaNodeTypeGroup|Model
     */
    public function getByTitle(string $title): MnemoSchemaNodeTypeGroup|Model
    {
        return MnemoSchemaNodeTypeGroup::query()->where('title', $title)->firstOrFail();
    }

    /**
     * Update
     *
     * @param MnemoSchemaNodeTypeGroup|Model $item
     * @param NodeTypeGroupDto|DtoInterface $dto
     * @return MnemoSchemaNodeTypeGroup
     */
    public function update(MnemoSchemaNodeTypeGroup|Model $item, NodeTypeGroupDto|DtoInterface $dto): MnemoSchemaNodeTypeGroup
    {
        $item->title = $dto->title;
        $item->description = $dto->description;
        $item->short_title = $dto->shortTitle;

        $item->save();

        return $item;
    }
}
