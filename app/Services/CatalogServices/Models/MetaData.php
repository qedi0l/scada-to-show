<?php

namespace App\Services\CatalogServices\Models;

readonly class MetaData
{
    public function __construct(
        public int $hardwareId,
        public int $signalId,
        public SignalData $signalData,
    )
    {
    }
}
