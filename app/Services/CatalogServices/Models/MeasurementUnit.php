<?php

namespace App\Services\CatalogServices\Models;

class MeasurementUnit
{
    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly string $shortTitle,
        public readonly int $unitTypeId,
    )
    {
    }
}
