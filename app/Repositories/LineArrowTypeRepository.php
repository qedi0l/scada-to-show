<?php

namespace App\Repositories;

use App\DTO\Interfaces\DtoInterface;
use App\DTO\LineArrowTypeDto;
use App\Models\MnemoSchemaLineArrowType;
use App\Models\MnemoSchemaLineType;
use App\Repositories\Interfaces\EntityRepository;
use Illuminate\Database\Eloquent\Model;

/**
 * Line Arrow Type Repository
 *
 * @method MnemoSchemaLineType getById(int $itemId)
 * @method MnemoSchemaLineType store(DtoInterface $dto)
 *
 */class LineArrowTypeRepository extends BaseRepository implements EntityRepository
{
    protected string $className = MnemoSchemaLineArrowType::class;

    /**
     * Get By Type
     *
     * @param string $title
     * @return MnemoSchemaLineArrowType|Model
     */
    public function getByTitle(string $title): MnemoSchemaLineArrowType|Model
    {
        return MnemoSchemaLineArrowType::query()->where('arrow_type_title', $title)->firstOrFail();
    }

    /**
     * Update
     *
     * @param Model|MnemoSchemaLineArrowType $item
     * @param DtoInterface|LineArrowTypeDto $dto
     * @return Model|MnemoSchemaLineArrowType
     */
    public function update(
        Model|MnemoSchemaLineArrowType $item,
        DtoInterface|LineArrowTypeDto $dto
    ): Model|MnemoSchemaLineArrowType {
        $item->arrow_type_title = $dto->arrowTypeTitle;

        $item->save();

        return $item;
    }
}
