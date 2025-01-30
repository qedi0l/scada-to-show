<?php

namespace App\DTO;

use App\DTO\Interfaces\DtoInterface;

/**
 * Line Options DTO
 */
class LineOptionsDto implements DtoInterface
{
    public function __construct(
        public int $lineId,
        public readonly string|null $text = null,
        public readonly int $typeId = 1,
        public readonly int|null $firstArrow = null,
        public readonly int|null $secondArrow = null,
    ) {
    }
}
