<?php

namespace Tests\Feature\Repositories\Interfaces;

use App\DTO\Interfaces\DtoInterface;
use App\Repositories\Interfaces\EntityRepository;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;

interface RepositoryTestsInterface
{
    /**
     * Get Filled Entity DTO Instance
     *
     * @return DtoInterface
     */
    public function getDto(): DtoInterface;

    /**
     * Get Entity Repository Instance
     *
     * @return EntityRepository
     */
    public function getRepository(): EntityRepository;

    /**
     * Get Entity Model Instance
     *
     * @return Model
     */
    public function getModel(): Model;

    /**
     * Get Entity Factory
     *
     * @return Factory
     */
    public function getFactory(): Factory;

    /**
     * Index Tests
     *
     * @return void
     */
    public function test_index(): void;

    /**
     * Get By ID Tests
     *
     * @return void
     */
    public function test_get_by_id(): void;

    /**
     * Store Tests
     *
     * @return void
     */
    public function test_store(): void;

    /**
     * Update Tests
     *
     * @return void
     */
    public function test_update(): void;

    /**
     * Destroy Tests
     *
     * @return void
     */
    public function test_destroy(): void;
}
