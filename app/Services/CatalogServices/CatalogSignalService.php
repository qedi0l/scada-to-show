<?php

namespace App\Services\CatalogServices;

use App\Contracts\ICatalogService;
use App\Services\CatalogServices\Models\Hardware;
use App\Services\CatalogServices\Models\MeasurementUnit;
use App\Services\CatalogServices\Models\MetaData;
use App\Services\CatalogServices\Models\Signal;
use App\Services\CatalogServices\Models\SignalData;
use App\Services\CatalogServices\Models\SignalType;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

/**
 * Catalog Signal Service
 */
class CatalogSignalService implements ICatalogService
{
    /** @var string $catalogBaseUrl */
    public string $catalogBaseUrl;

    /** @var string $catalogBaseUrl */
    public string $metaDataUrl;

    /** @var string $catalogBaseUrl */
    public string $readableSignalsUrl;

    /** @var string $catalogBaseUrl */
    public string $writableSignalsUrl;

    /** @var string $hardwareHierarchyUrl */
    public string $hardwareHierarchyUrl;

    public function __construct()
    {
        $this->catalogBaseUrl = config('catalog.base_url');
        $this->metaDataUrl = config('catalog.signals_metadata_route');
        $this->readableSignalsUrl = config('catalog.readable_signals_endpoint');
        $this->writableSignalsUrl = config('catalog.writable_signals_endpoint');
        $this->hardwareHierarchyUrl = config('catalog.hardware_hierarchy_endpoint');
    }

    /**
     * @return Collection|null
     */
    public function getAllReadableSignals(): ?Collection
    {
        $response = Http::get($this->readableSignalsUrl);

        if ($response->failed()) {
            return null;
        }

        return $this->convertToSignalCollection($response->json());
    }

    /**
     * @return Collection<Signal>|null
     */
    public function getAllWritableSignals(): ?Collection
    {
        $response = Http::get($this->writableSignalsUrl);

        if ($response->failed()) {
            return null;
        }

        return $this->convertToSignalCollection($response->json());
    }

    /**
     * @return Collection<Signal>|null
     */
    public function getReadableSignalsByHardwareCode(int $hardwareCode): ?Collection
    {
        $response = Http::get($this->readableSignalsUrl . '/' . $hardwareCode);

        if ($response->failed()) {
            return null;
        }

        return $this->convertToSignalCollection($response->json());
    }

    /**
     * @param int $hardwareCode
     * @return Collection<Signal>|null
     */
    public function getWritableSignalsByHardwareCode(int $hardwareCode): ?Collection
    {
        $response = Http::get($this->writableSignalsUrl . '/' . $hardwareCode);

        if ($response->failed()) {
            return null;
        }

        return $this->convertToSignalCollection($response->json());
    }

    /**
     * @param int $hardwareCode
     * @param array|null $signalsId
     * @return Collection<MetaData>|null
     */
    public function postMetaData(int $hardwareCode, array|null $signalsId = null): ?Collection
    {
        $requestData = [
            'hardwareId' => $hardwareCode
        ];

        if ($signalsId) {
            $requestData['signalsId'] = $signalsId;
        }

        $response = Http::post($this->metaDataUrl, [$requestData]);

        if ($response->failed()) {
            return null;
        }

        return $this->convertToMetaDataCollection($response->json());
    }

    /**
     * @param string|null $parentId
     * @return Collection<Hardware>|null
     */
    public function getHardwareHierarchy(string|null $parentId = null): ?Collection
    {
        if (!$parentId) {
            $response = Http::get($this->hardwareHierarchyUrl);
        } else {
            $response = Http::get($this->hardwareHierarchyUrl, [
                'parentId' => $parentId
            ]);
        }

        if ($response->failed()) {
            return null;
        }

        return $this->convertToHardwareCollection($response->json());
    }

    /**
     * @param array $signalResponse
     * @return Collection<Signal>
     */
    private function convertToSignalCollection(array $signalResponse): Collection
    {
        return collect($signalResponse)
            ->map(function ($signal) {
                return new Signal(
                    transportSignalId: $signal['transportSignalId'],
                    title: $signal['title'],
                    description: $signal['description'],
                    shortTitle: $signal['shortTitle'],
                    transportHardwareId: $signal['transportHardwareId'],
                    signalTypeId: $signal['signalTypeId'],
                    read: $signal['read'],
                    write: $signal['write'],
                    measurementUnitId: $signal['measurementUnitId'],
                    signalGroupId: $signal['signalGroupId'],
                    signalType: new SignalType(
                        id: $signal['signalType']['id'],
                        title: $signal['signalType']['title'],
                        shortTitle: $signal['signalType']['shortTitle'],
                    ),
                    measurementUnit: $signal['measurementUnit']
                        ? new MeasurementUnit(
                            id: $signal['measurementUnit']['id'],
                            title: $signal['measurementUnit']['title'],
                            shortTitle: $signal['measurementUnit']['shortTitle'],
                            unitTypeId: $signal['measurementUnit']['unitTypeId'],
                        )
                        : null,
                );
            });
    }

    /**
     * @param array $metaDataResponse
     * @return Collection<MetaData>
     */
    private function convertToMetaDataCollection(array $metaDataResponse): Collection
    {
        return collect($metaDataResponse)
            ->map(function (array $metaData) {
                return new MetaData(
                    hardwareId: $metaData['hardwareId'],
                    signalId: $metaData['signalId'],
                    signalData: new SignalData(
                        id: $metaData['signalData']['id'],
                        label: $metaData['signalData']['label'],
                        name: $metaData['signalData']['name'],
                        signalType: $metaData['signalData']['signalType'],
                        cutUnit: $metaData['signalData']['cutUnit'],
                        unit: $metaData['signalData']['unit'],
                        color: $metaData['signalData']['color'],
                    )
                );
            });
    }

    /**
     * @param array $hardwareResponse
     * @return Collection<Hardware>
     */
    private function convertToHardwareCollection(array $hardwareResponse): Collection
    {
        return collect($hardwareResponse)
            ->map(function (array $hardware) {
                return new Hardware(
                    id: $hardware['id'],
                    isHasChildren: $hardware['isHasChildren'],
                    title: $hardware['title'],
                    shortTitle: $hardware['shortTitle'],
                    childQuery: array_key_exists('childQuery', $hardware) ? $hardware['childQuery'] : null,
                    parentId: array_key_exists('parentId', $hardware) ? $hardware['parentId'] : null,
                    type: array_key_exists('type', $hardware) ? $hardware['type'] : null,
                );
            });
    }
}
