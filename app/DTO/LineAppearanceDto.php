<?php

namespace App\DTO;

use App\DTO\Interfaces\DtoInterface;

/**
 * Line Appearance DTO
 */
final class LineAppearanceDto implements DtoInterface
{
    public function __construct(
        public int $lineId,
        public readonly string|null $color = null,
        public readonly int $opacity = 100,
        public readonly int $width = 1,
    ) {
    }
}
