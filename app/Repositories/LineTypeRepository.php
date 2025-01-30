<?php

namespace App\Repositories;

use App\DTO\Interfaces\DtoInterface;
use App\DTO\LineTypeDto;
use App\Models\MnemoSchemaLineType;
use App\Repositories\Interfaces\EntityRepository;
use Illuminate\Database\Eloquent\Model;

/**
 * Line Type Repository
 *
 * @method MnemoSchemaLineType getById(int $itemId)
 * @method MnemoSchemaLineType store(DtoInterface $dto)
 */
class LineTypeRepository extends BaseRepository implements EntityRepository
{
    protected string $className = MnemoSchemaLineType::class;

    /**
     * Get By Type
     *
     * @param string $type
     * @return MnemoSchemaLineType|Model
     */
    public function getByType(string $type): MnemoSchemaLineType|Model
    {
        return MnemoSchemaLineType::query()->where('type', $type)->firstOrFail();
    }


    /**
     * Update
     *
     * @param Model|MnemoSchemaLineType $item
     * @param DtoInterface|LineTypeDto $dto
     * @return Model
     */
    public function update(Model|MnemoSchemaLineType $item, DtoInterface|LineTypeDto $dto): Model
    {
        $item->type = $dto->type;

        $item->save();

        return $item;
    }
}
