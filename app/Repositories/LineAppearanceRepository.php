<?php

namespace App\Repositories;

use App\DTO\Interfaces\DtoInterface;
use App\DTO\LineAppearanceDto;
use App\Models\MnemoSchemaLineAppearance;
use App\Repositories\Interfaces\EntityRepository;
use Illuminate\Database\Eloquent\Model;

/**
 * Line Appearance Repository
 *
 * @method MnemoSchemaLineAppearance getById(int $itemId)
 * @method MnemoSchemaLineAppearance store(DtoInterface $dto)
 */
class LineAppearanceRepository extends BaseRepository implements EntityRepository
{
    protected string $className = MnemoSchemaLineAppearance::class;

    /**
     * Get By Line ID
     *
     * @param int $lineId
     * @return MnemoSchemaLineAppearance|Model
     */
    public function getByLineId(int $lineId): MnemoSchemaLineAppearance|Model
    {
        return MnemoSchemaLineAppearance::query()->where("line_id", $lineId)->firstOrFail();
    }

    public function update(MnemoSchemaLineAppearance|Model $item, LineAppearanceDto|DtoInterface $dto):
    MnemoSchemaLineAppearance|Model {
        $item->line_id = $dto->lineId;
        $item->color = $dto->color;
        $item->opacity = $dto->opacity;
        $item->width = $dto->width;

        $item->save();

        return $item;
    }
}
