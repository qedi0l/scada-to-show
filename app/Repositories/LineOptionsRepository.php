<?php

namespace App\Repositories;

use App\DTO\Interfaces\DtoInterface;
use App\DTO\LineOptionsDto;
use App\Models\MnemoSchemaLineOptions;
use App\Repositories\Interfaces\EntityRepository;
use Illuminate\Database\Eloquent\Model;

/**
 * Line Options Repository
 *
 * @method MnemoSchemaLineOptions getById(int $itemId)
 * @method MnemoSchemaLineOptions store(DtoInterface $dto)
 *
 */
class LineOptionsRepository extends BaseRepository implements EntityRepository
{
    protected string $className = MnemoSchemaLineOptions::class;

    /**
     * Get By Line ID
     * @param int $lineId
     * @return MnemoSchemaLineOptions|Model
     */
    public function getByLineId(int $lineId): MnemoSchemaLineOptions|Model
    {
        return MnemoSchemaLineOptions::query()->where('line_id', $lineId)->firstOrFail();
    }

    /**
     * Update
     *
     * @param MnemoSchemaLineOptions|Model $item
     * @param LineOptionsDto|DtoInterface $dto
     * @return MnemoSchemaLineOptions|Model
     */
    public function update(MnemoSchemaLineOptions|Model $item, LineOptionsDto|DtoInterface $dto): MnemoSchemaLineOptions|Model
    {
        $item->line_id = $dto->lineId;
        $item->text = $dto->text;
        $item->type_id = $dto->typeId;
        $item->first_arrow = $dto->firstArrow;
        $item->second_arrow = $dto->secondArrow;

        $item->save();

        return $item;
    }


}
