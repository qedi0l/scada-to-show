<?php

namespace Tests\Feature\Services;

use App\Services\CatalogServices\CatalogSignalService;
use App\Services\CatalogServices\Models\Hardware;
use App\Services\CatalogServices\Models\MetaData;
use App\Services\CatalogServices\Models\Signal;
use Illuminate\Support\Collection;
use Tests\TestCase;

class CatalogSignalServiceTest extends TestCase
{
    public function test_get_all_readable_signals(): void
    {
        $service = new CatalogSignalService();

        $response = $service->getAllReadableSignals();

        $this->assertInstanceOf(Collection::class, $response);

        if ($response->isNotEmpty()) {
            $this->assertInstanceOf(Signal::class, $response->first());
        }
    }

    public function test_get_all_writable_signals(): void
    {
        $service = new CatalogSignalService();

        $response = $service->getAllWritableSignals();

        $this->assertInstanceOf(Collection::class, $response);

        if ($response->isNotEmpty()) {
            $this->assertInstanceOf(Signal::class, $response->first());
        }
    }

    public function test_get_readable_signal_by_hardware_code()
    {
        $service = new CatalogSignalService();

        $response = $service->getAllReadableSignals();

        if ($response->isNotEmpty()) {
            $response = $service->getReadableSignalsByHardwareCode($response->first()->transportHardwareId);

            $this->assertInstanceOf(Collection::class, $response);
            $this->assertNotEmpty($response);
            $this->assertInstanceOf(Signal::class, $response->first());
        }
    }

    public function test_get_writable_signal_by_hardware_code()
    {
        $service = new CatalogSignalService();

        $response = $service->getAllWritableSignals();

        if ($response->isNotEmpty()) {
            $response = $service->getWritableSignalsByHardwareCode($response->first()->transportHardwareId);

            $this->assertInstanceOf(Collection::class, $response);
            $this->assertNotEmpty($response);
            $this->assertInstanceOf(Signal::class, $response->first());
        }
    }

    public function test_meta_data()
    {
        $service = new CatalogSignalService();

        $response = $service->getAllReadableSignals();

        if ($response->isNotEmpty()) {
            $response = $service->postMetaData($response->first()->transportHardwareId);

            $this->assertInstanceOf(Collection::class, $response);
            $this->assertNotEmpty($response);
            $this->assertInstanceOf(MetaData::class, $response->first());
        }
    }

    public function test_meta_data_with_signals()
    {
        $service = new CatalogSignalService();

        $response = $service->getAllReadableSignals();

        if ($response->isNotEmpty()) {
            $hardwareCode = $response->first()->transportHardwareId;
            $signals = [$response->first()->transportSignalId];

            $response = $service->postMetaData($hardwareCode, $signals);

            $this->assertInstanceOf(Collection::class, $response);
            $this->assertNotEmpty($response);
            $this->assertInstanceOf(MetaData::class, $response->first());
            $this->assertCount(count($signals), $response);
        }
    }

    public function test_get_hardware_hierarchy()
    {
        $service = new CatalogSignalService();

        $response = $service->getHardwareHierarchy();
        $this->assertInstanceOf(Collection::class, $response);

        if ($response->isNotEmpty()) {
            $this->assertInstanceOf(Hardware::class, $response->first());

            $parentId = $response->first()->id;
            $response = $service->getHardwareHierarchy($parentId);
            $this->assertInstanceOf(Collection::class, $response);
        }
    }
}
