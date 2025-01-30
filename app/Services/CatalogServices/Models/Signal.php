<?php

namespace App\Services\CatalogServices\Models;


readonly class Signal
{
    public function __construct(
        public int $transportSignalId,
        public string $title,
        public string $description,
        public string $shortTitle,
        public int $transportHardwareId,
        public int $signalTypeId,
        public bool $read,
        public bool $write,
        public ?int $measurementUnitId = null,
        public ?int $signalGroupId = null,
        public SignalType $signalType,
        public ?MeasurementUnit $measurementUnit = null,
    )
    {
    }
}
