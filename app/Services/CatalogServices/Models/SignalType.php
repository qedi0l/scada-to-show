<?php

namespace App\Services\CatalogServices\Models;

class SignalType
{
    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly string $shortTitle,
    )
    {
    }
}
