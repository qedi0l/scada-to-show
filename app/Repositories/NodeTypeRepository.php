<?php

namespace App\Repositories;

use App\DTO\Interfaces\DtoInterface;
use App\DTO\NodeTypeDto;
use App\Exceptions\Repositories\WrongNodeTypeDeletionException;
use App\Models\MnemoSchemaNodeType;
use App\Models\Types\NodeTypeType;
use App\Repositories\Filters\FilterInterface;
use App\Repositories\Filters\NodeFilter;
use App\Repositories\Filters\NodeTypeFilter;
use App\Repositories\Interfaces\EntityRepository;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Node Type Repository
 *
 * @method MnemoSchemaNodeType getById(int $itemId)
 * @method MnemoSchemaNodeType store(DtoInterface $dto)
 */
class NodeTypeRepository extends BaseRepository implements EntityRepository
{
    protected string $className = MnemoSchemaNodeType::class;

    protected NodeRepository $nodeRepository;

    public function __construct()
    {
        $this->nodeRepository = new NodeRepository();
    }

    /**
     * Index
     *
     * @param NodeTypeFilter|FilterInterface|null $filter
     * @return Collection|array
     */
    public function index(NodeTypeFilter|null|Filters\FilterInterface $filter = null): Collection|array
    {
        return MnemoSchemaNodeType::query()
            ->when(
                $filter,
                function (Builder $query, NodeTypeFilter $filter) {
                    $query
                        ->when(
                            !is_null($filter->svgNotNull),
                            function (Builder $query) use ($filter) {
                                if ($filter->svgNotNull) {
                                    $query->whereNotNull('svg');
                                } else {
                                    $query->whereNull('svg');
                                }
                            }
                        )
                        ->when(
                            $filter->type,
                            fn(Builder $query, string $type) => $query->where('type', $type)
                        );
                }
            )
            ->get();
    }

    /**
     * Get by Type
     *
     * @param string $type
     * @return Model|Builder|MnemoSchemaNodeType
     */
    public function getByType(string $type): Model|Builder|MnemoSchemaNodeType
    {
        return MnemoSchemaNodeType::query()->where('type', $type)->firstOrFail();
    }

    /**
     * Update
     *
     * @param MnemoSchemaNodeType|Model $item
     * @param NodeTypeDto|DtoInterface $dto
     * @return MnemoSchemaNodeType
     */
    public function update(MnemoSchemaNodeType|Model $item, NodeTypeDto|DtoInterface $dto): MnemoSchemaNodeType
    {
        $item->type = $dto->type;
        $item->hardware_type = $dto->hardwareType;
        $item->title = $dto->title;
        $item->svg = $dto->svg;
        $item->node_type_group_id = $dto->nodeTypeGroupId;
        $item->service_type = $dto->serviceType;

        $item->save();

        return $item;
    }

    /**
     * Destroy
     *
     * @param int|Model $item
     * @return bool|null
     * @throws Exception
     */
    public function destroy(int|Model $item): ?bool
    {
        if (is_int($item)) {
            $item = $this->getById($item);
        }

        // Check delete default types
        $defaultNodeType = $this->getByType(NodeTypeType::Default->value);
        $transparentNodeType = $this->getByType(NodeTypeType::Transparent->value);

        if ($item->is($defaultNodeType) || $item->is($transparentNodeType)) {
            throw new WrongNodeTypeDeletionException();
        }

        DB::beginTransaction();

        // Set default type to used nodes
        $filter = (new NodeFilter())->setTypeId($item->getKey());
        $nodeIds = $this->nodeRepository->index($filter)->pluck('id')->toArray();
        $this->nodeRepository->massUpdate($nodeIds, ['type_id' => $defaultNodeType->getKey()]);

        // Delete node type
        $result = $item->delete();

        DB::commit();

        return $result;
    }
}
