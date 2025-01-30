<?php

namespace App\Contracts;

use App\Services\CatalogServices\Models\Signal;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;

interface ICatalogService
{
    /**
     * Get all signals that available for reading
     * @return Collection<Signal>|null
     */
    public function getAllReadableSignals(): ?Collection;

    /**
     * Get all signals that available for writing
     * @return Collection|null
     */
    public function getAllWritableSignals(): ?Collection;

    /**
     * Get all signals that available for reading by hardware code
     * @param int $hardwareCode
     * @return Collection|null
     */
    public function getReadableSignalsByHardwareCode(int $hardwareCode): ?Collection;

    /**
     * Get all signals that available for writing by hardware code
     * @param int $hardwareCode
     * @return Collection|null
     */
    public function getWritableSignalsByHardwareCode(int $hardwareCode): ?Collection;

    /**
     * Send meta data to receive signal data
     * @param int $hardwareCode
     * @param array|null $signalsId
     * @return Collection|null
     */
    public function postMetaData(int $hardwareCode, array|null $signalsId = null): ?Collection;

    /**
     * Get hardware hierarchy
     * @param string|null $parentId
     * @return Collection|null
     */
    public function getHardwareHierarchy(string|null $parentId): ?Collection;
}
