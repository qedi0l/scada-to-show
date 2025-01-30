<?php

namespace App\Repositories;

use App\DTO\Interfaces\DtoInterface;
use App\DTO\LineDto;
use App\Models\MnemoSchemaLine;
use App\Repositories\Filters\FilterInterface;
use App\Repositories\Filters\LineFilter;
use App\Repositories\Interfaces\EntityRepository;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * Line Repository
 *
 * @method MnemoSchemaLine getById(int $itemId)
 * @method MnemoSchemaLine store(DtoInterface $dto)
 */
class LineRepository extends BaseRepository implements EntityRepository
{
    protected string $className = MnemoSchemaLine::class;

    protected LineOptionsRepository $lineOptionsRepository;
    protected LineAppearanceRepository $lineAppearanceRepository;

    public function __construct()
    {
        $this->lineOptionsRepository = new LineOptionsRepository();
        $this->lineAppearanceRepository = new LineAppearanceRepository();
    }

    /**
     * Index
     *
     * @param LineFilter|FilterInterface|null $filter
     * @return array|Collection
     */
    public function index(LineFilter|FilterInterface|null $filter = null): array|Collection
    {
        return MnemoSchemaLine::query()
            ->when(
                $filter,
                function (Builder $query, LineFilter $filter) {
                    $query
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
     * @param MnemoSchemaLine|Model $item
     * @param LineDto|DtoInterface $dto
     * @return MnemoSchemaLine|Model
     * @throws Exception
     */
    public function update(MnemoSchemaLine|Model $item, LineDto|DtoInterface $dto): MnemoSchemaLine|Model
    {
        DB::beginTransaction();

        $item->schema_id = $dto->schemaId;
        $item->first_node = $dto->firstNodeId;
        $item->second_node = $dto->secondNodeId;
        $item->source_position = $dto->sourcePosition;
        $item->target_position = $dto->targetPosition;

        $item->save();

        if (!is_null($dto->options)) {
            if ($dto->options->lineId === 0) {
                $dto->options->lineId = $item->getKey();
                $this->lineOptionsRepository->store($dto->options);
            } else {
                $lineOptions = $this->lineOptionsRepository->getByLineId($item->getKey());
                $this->lineOptionsRepository->update($lineOptions, $dto->options);
            }
        }

        if (!is_null($dto->appearance)) {
            if ($dto->appearance->lineId === 0) {
                $dto->appearance->lineId = $item->getKey();
                $this->lineAppearanceRepository->store($dto->appearance);
            } else {
                $lineAppearance = $this->lineAppearanceRepository->getByLineId($item->getKey());
                $this->lineAppearanceRepository->update($lineAppearance, $dto->appearance);
            }
        }

        DB::commit();

        return $item;
    }

    /**
     * Destroy
     *
     * @param int|Model|MnemoSchemaLine $item
     * @return bool|null
     * @throws Exception
     */
    public function destroy(int|Model|MnemoSchemaLine $item): ?bool
    {
        DB::beginTransaction();

        if (is_int($item)) {
            $item = $this->getById($item);
        }

        $item->options()->delete();
        $item->appearance()->delete();

        $result = $item->delete();

        DB::commit();

        return $result;
    }

    /**
     * Destroy All Lines of Schema
     *
     * @param int $schemaId
     * @return bool
     * @throws Throwable
     */
    public function destroyBySchemaId(int $schemaId): bool
    {
        $filter = (new LineFilter())->setSchemaId($schemaId);
        $this
            ->index($filter)
            ->each(function (MnemoSchemaLine $line) {
                $this->destroy($line);
            });

        return true;
    }
}
