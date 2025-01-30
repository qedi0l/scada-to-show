<?php

namespace Tests\Feature\Repositories\Traits;

use App\Repositories\Interfaces\EntityRepository;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Tests\Feature\Repositories\Interfaces\RepositoryTestsInterface;

/**
 * @mixin RepositoryTestsInterface
 */
trait RepositoryMethods
{
    public function getRepository(): EntityRepository
    {
        return new $this->repositoryClass();
    }

    /**
     * @return Model
     */
    public function getModel(): Model
    {
        return new $this->modelClass();
    }

    public function getFactory(): Factory
    {
        return $this->getModel()::factory();
    }

    public function test_index(): void
    {
        $result = $this->getRepository()->index();

        $this->assertInstanceOf(Collection::class, $result);
    }

    public function test_get_by_id(): void
    {
        $item = $this->getFactory()->create();

        $result = $this->getRepository()->getById($item->getKey());

        $this->assertInstanceOf(Model::class, $result);
    }

    public function test_store(): void
    {
        $result = $this->getRepository()->store($this->getDto());

        $this->assertInstanceOf(Model::class, $result);
    }

    public function test_update(): void
    {
        $item = $this->getFactory()->create();

        $result = $this->getRepository()->update($item, $this->getDto());

        $this->assertInstanceOf(Model::class, $result);
    }

    public function test_destroy(): void
    {
        // Test Destroy By Model
        $item = $this->getFactory()->create();
        $result = $this->getRepository()->destroy($item);
        $this->assertTrue($result);

        // Test Destroy By ID
        $item = $this->getFactory()->create();
        $result = $this->getRepository()->destroy($item->getKey());
        $this->assertTrue($result);
    }
}
