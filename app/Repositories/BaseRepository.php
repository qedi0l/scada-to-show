<?php

namespace App\Repositories;

use App\DTO\Interfaces\DtoInterface;
use App\Exceptions\Repositories\MissingClassNameException;
use App\Repositories\Filters\FilterInterface;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository
{
    protected string $className = '';

    /**
     * @return class-string<Model>
     * @throws Exception
     */
    public function getClassName(): string
    {
        if (empty($this->className)) {
            throw new MissingClassNameException(get_class($this));
        }
        return $this->className;
    }

    /**
     * Index
     *
     * @param FilterInterface|null $filter Entity Filter
     * @return array|Collection
     * @throws Exception
     */
    public function index(FilterInterface|null $filter = null): array|Collection
    {
        $class = $this->getClassName();

        return $class::query()
            ->get();
    }

    /**
     * Get Item By ID
     *
     * @param int $itemId Item ID
     * @return Model
     * @throws Exception
     */
    public function getById(int $itemId): Model
    {
        $class = $this->getClassName();

        return $class::query()->findOrFail($itemId);
    }

    /**
     * Store
     *
     * @param DtoInterface $dto Item DTO
     * @return Model
     * @throws Exception
     */
    public function store(DtoInterface $dto): Model
    {
        $class = $this->getClassName();

        return $this->update(new $class(), $dto);
    }

    /**
     * Destroy
     *
     * @param Model|int $item Item ID or Entity Item
     * @return bool|null
     * @throws Exception
     */
    public function destroy(Model|int $item): ?bool
    {
        if (is_int($item)) {
            $item = $this->getById($item);
        }

        return $item->delete();
    }
}
