<?php

namespace App\DTO;

use App\DTO\Interfaces\DtoInterface;

final class LineTypeDto implements DtoInterface
{
    public function __construct(
        public readonly string $type,
    )
    {

    }
}
