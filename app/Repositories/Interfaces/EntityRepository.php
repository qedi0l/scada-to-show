<?php

namespace App\Repositories\Interfaces;

use App\DTO\Interfaces\DtoInterface;
use App\Repositories\Filters\FilterInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Entity Repository Interface
 */
interface EntityRepository
{
    /**
     * Index
     *
     * @param FilterInterface|null $filter Entity Filter
     * @return array|Collection
     */
    public function index(FilterInterface|null $filter = null): array|Collection;

    /**
     * Get By ID
     *
     * @param int $itemId Item ID
     * @return Model
     */
    public function getById(int $itemId): Model;

    /**
     * Store
     *
     * @param DtoInterface $dto Item DTO
     * @return Model
     */
    public function store(DtoInterface $dto): Model;

    /**
     * Update
     *
     * @param Model $item Entity Item
     * @param DtoInterface $dto Item DTO
     * @return Model
     */
    public function update(Model $item, DtoInterface $dto): Model;

    /**
     * Destroy
     *
     * @param int|Model $item Item ID
     * @return bool|null
     */
    public function destroy(int|Model $item): ?bool;
}
