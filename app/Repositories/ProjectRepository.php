<?php

namespace App\Repositories;

use App\DTO\Interfaces\DtoInterface;
use App\DTO\ProjectDto;
use App\Models\MnemoSchemaProject;
use App\Repositories\Interfaces\EntityRepository;
use Illuminate\Database\Eloquent\Model;

/**
 * Project Repository
 *
 * @method MnemoSchemaProject getById(int $itemId)
 * @method MnemoSchemaProject store(DtoInterface $dto)
 */
class ProjectRepository extends BaseRepository implements EntityRepository
{
    protected string $className = MnemoSchemaProject::class;

    /**
     * Update
     *
     * @param Model|MnemoSchemaProject $item
     * @param DtoInterface|ProjectDto $dto
     * @return Model|MnemoSchemaProject
     */
    public function update(Model|MnemoSchemaProject $item, DtoInterface|ProjectDto $dto): Model|MnemoSchemaProject
    {
        $item->title = $dto->title;
        $item->description = $dto->description;
        $item->short_title = $dto->shortTitle;

        $item->save();

        return $item;
    }
}
