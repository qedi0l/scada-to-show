<?php

namespace App\Repositories;

use App\DTO\Interfaces\DtoInterface;
use App\DTO\NodeDto;
use App\Models\MnemoSchemaNode;
use App\Models\Types\NodeTypeType;
use App\Repositories\Filters\FilterInterface;
use App\Repositories\Filters\NodeFilter;
use App\Repositories\Interfaces\EntityRepository;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * Node Repositories
 *
 * @method MnemoSchemaNode getById(int $itemId)
 * @method MnemoSchemaNode store(DtoInterface $dto)
 */
class NodeRepository extends BaseRepository implements EntityRepository
{
    protected string $className = MnemoSchemaNode::class;

    /**
     * Index
     *
     * @param NodeFilter|FilterInterface|null $filter
     * @return Collection
     */
    public function index(NodeFilter|null|Filters\FilterInterface $filter = null): Collection
    {
        return MnemoSchemaNode::query()
            ->when(
                $filter,
                function (Builder $query, NodeFilter $filter) {
                    $query
                        ->when(
                            $filter->schemaId,
                            fn(Builder $query, int $schemaId) => $query->where('schema_id', $schemaId)
                        )
                        ->when(
                            $filter->schemaName,
                            fn(Builder $query, string $schemaName) => $query
                                ->whereHas('schema', fn(Builder $query) => $query->where('name', $schemaName))
                        )
                        ->when(
                            $filter->typeId,
                            fn(Builder $query, int $typeId) => $query->where('type_id', $typeId)
                        )
                        ->when(
                            $filter->parentNodeId,
                            fn(Builder $query, int $parentNodeId) => $query
                                ->whereHas('options', fn(Builder $query) => $query->where('parent_id', $parentNodeId))
                        )
                        ->when(
                            !is_null($filter->hasNotEmptyParameterCode),
                            function (Builder $query) use ($filter) {
                                if ($filter->hasNotEmptyParameterCode) {
                                    $query->whereHas(
                                        'options',
                                        fn(Builder $query) => $query->whereNotNull('parameter_code')
                                    );
                                } else {
                                    $query->whereHas(
                                        'options',
                                        fn(Builder $query) => $query->whereNull('parameter_code')
                                    );
                                }
                            }
                        )
                        ->when(
                            !is_null($filter->hasParameterCodes),
                            function (Builder $query) use ($filter) {
                                $query->whereHas(
                                    'options',
                                    fn(Builder $query) => $query->whereIn(
                                        'parameter_code',
                                        Arr::wrap($filter->hasParameterCodes)
                                    )
                                );
                            }
                        )
                        ->when(
                            !is_null($filter->hasNoParameterCodes),
                            function (Builder $query) use ($filter) {
                                $query->whereHas(
                                    'options',
                                    fn(Builder $query) => $query->whereNotIn(
                                        'parameter_code',
                                        Arr::wrap($filter->hasNoParameterCodes)
                                    )
                                );
                            }
                        );
                }
            )
            ->get();
    }

    /**
     * Update
     *
     * @param MnemoSchemaNode|Model $item
     * @param NodeDto|DtoInterface $dto
     * @return MnemoSchemaNode|Model
     * @throws Exception
     */
    public function update(MnemoSchemaNode|Model $item, NodeDto|DtoInterface $dto): MnemoSchemaNode|Model
    {
        DB::beginTransaction();

        $item->title = $dto->title;
        $item->schema_id = $dto->schemaId;
        $item->group_id = $dto->groupId;
        $item->type_id = $dto->typeId;

        $originalTypeId = $item->getOriginal('type_id');

        $item->save();

        // Destroy link if need
        if ($item->wasChanged('type_id')) {
            $nodeTypeRepository = new NodeTypeRepository();
            $linkType = $nodeTypeRepository->getByType(NodeTypeType::Link->value);
            if ($originalTypeId == $linkType->id) {
                $item->link()->delete();
            }
        }

        // Handle Node Options
        if (!is_null($dto->options)) {
            $nodeOptionsRepository = new NodeOptionsRepository();
            if ($dto->options->nodeId === 0) {
                $dto->options->nodeId = $item->id;
                $nodeOptionsRepository->store($dto->options);
            } else {
                $nodeOptions = $nodeOptionsRepository->getByNodeId($item->id);
                $nodeOptionsRepository->update($nodeOptions, $dto->options);
            }
        }

        // Hande Node Appearance
        if (!is_null($dto->appearance)) {
            $nodeAppearanceRepository = new NodeAppearanceRepository();
            if ($dto->appearance->nodeId === 0) {
                $dto->appearance->nodeId = $item->id;
                $nodeAppearanceRepository->store($dto->appearance);
            } else {
                $nodeAppearance = $nodeAppearanceRepository->getByNodeId($item->id);
                $nodeAppearanceRepository->update($nodeAppearance, $dto->appearance);
            }
        }

        // Handle Node Geometry
        if (!is_null($dto->geometry)) {
            $nodeGeometryRepository = new NodeGeometryRepository();
            if ($dto->geometry->nodeId === 0) {
                $dto->geometry->nodeId = $item->id;
                $nodeGeometryRepository->store($dto->geometry);
            } else {
                $nodeGeometry = $nodeGeometryRepository->getByNodeId($item->id);
                $nodeGeometryRepository->update($nodeGeometry, $dto->geometry);
            }
        }

        // Handle Node Link
        if (!is_null($dto->link)) {
            $nodeLinkRepository = new NodeLinkRepository();
            if ($dto->link->nodeId === 0) {
                $dto->link->nodeId = $item->id;
                $nodeLinkRepository->store($dto->link);
            } else {
                $nodeLink = $nodeLinkRepository->getByNodeId($item->id);
                $nodeLinkRepository->update($nodeLink, $dto->link);
            }
        }

        DB::commit();

        return $item;
    }

    /**
     * Update Node Type
     *
     * @param MnemoSchemaNode $node
     * @param int $typeId
     * @return MnemoSchemaNode|Model
     * @throws Throwable
     */
    public function updateTypeId(MnemoSchemaNode $node, int $typeId): MnemoSchemaNode|Model
    {
        $dto = new NodeDto(
            title: $node->title,
            schemaId: $node->schema_id,
            groupId: $node->group_id,
            typeId: $typeId,
        );
        return $this->update($node, $dto);
    }

    /**
     * Mass Update
     *
     * @param array $nodeIds
     * @param array $changes
     * @return bool|int Number of Updated Items
     */
    public function massUpdate(array $nodeIds, array $changes): bool|int
    {
        return MnemoSchemaNode::query()
            ->whereIn('id', $nodeIds)
            ->update($changes);
    }

    /**
     * @param int $nodeId
     * @return int|null
     * @throws Exception
     */
    public function getHardwareCodeByNodeId(int $nodeId): ?int
    {
        $node = $this->getById($nodeId);

        return $node->options?->hardware_code;
    }

    public function getHierarchy(string $schemaName, int $nodeId)
    {
        $filter = (new NodeFilter())
            ->setSchemaName($schemaName)
            ->setParentNodeId($nodeId);

        return $this
            ->index($filter)
            ->load(['options', 'appearance', 'node_type', 'children_options'])
            ->mapWithKeys(fn(MnemoSchemaNode $node) => [$node->getKey() => $node]);
    }

    /**
     * Destroy
     *
     * @param int|MnemoSchemaNode|Model $item Node ID or Node Model
     * @return bool|null
     * @throws Exception
     */
    public function destroy(int|MnemoSchemaNode|Model $item): ?bool
    {
        DB::beginTransaction();

        if (is_int($item)) {
            $item = $this->getById($item);
        }

        $item->geometry()->delete();
        $item->options()->delete();
        $item->appearance()->delete();
        $item->link()->delete();
        $item->commands()->delete();
        $item->to_lines()->delete();
        $item->from_lines()->delete();

        $result = $item->delete();

        DB::commit();

        return $result;
    }

    /**
     * @param int $schemaId
     * @return bool
     * @throws Throwable
     */
    public function destroyBySchemaId(int $schemaId): bool
    {
        $filter = (new NodeFilter())->setSchemaId($schemaId);
        $this
            ->index($filter)
            ->each(function (MnemoSchemaNode $node) {
                $this->destroy($node);
            });

        return true;
    }
}
