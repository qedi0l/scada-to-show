<?php

namespace App\Services\CatalogServices\Models;

readonly class SignalData
{
public function __construct(
    public int $id,
    public string $label,
    public string $name,
    public string $signalType,
    public ?string $cutUnit = null,
    public ?string $unit = null,
    public ?string $color = null,
)
{
}
}
