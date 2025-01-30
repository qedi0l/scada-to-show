<?php

namespace App\Repositories;

use App\DTO\Interfaces\DtoInterface;
use App\DTO\MethodTitleMatchingDto;
use App\Models\MethodTitleMatching;
use App\Repositories\Interfaces\EntityRepository;
use Illuminate\Database\Eloquent\Model;

/**
 * Method Title Matching Repository
 *
 * @method MethodTitleMatching getById(int $itemId)
 * @method MethodTitleMatching store(DtoInterface $dto)
 *
 */
class MethodTitleMatchingRepository extends BaseRepository implements EntityRepository
{
    protected string $className = MethodTitleMatching::class;

    /**
     * Update
     *
     * @param Model|MethodTitleMatching $item
     * @param DtoInterface|MethodTitleMatchingDto $dto
     * @return Model|MethodTitleMatching
     */
    public function update(
        Model|MethodTitleMatching $item,
        DtoInterface|MethodTitleMatchingDto $dto
    ): Model|MethodTitleMatching {
        $item->frontend_method_title = $dto->frontendMethodTitle;
        $item->receiver_title = $dto->receiverTitle;
        $item->concrete_command_title = $dto->concreteCommandTitle;
        $item->undo_receiver_title = $dto->undoReceiverTitle;

        $item->save();

        return $item;
    }
}
