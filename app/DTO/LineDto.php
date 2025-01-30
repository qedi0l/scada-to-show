<?php

namespace App\DTO;

use App\DTO\Interfaces\DtoInterface;

/**
 * Line DTO
 */
final class LineDto implements DtoInterface
{
    public function __construct(
        public readonly int $schemaId,
        public readonly int $firstNodeId,
        public readonly int $secondNodeId,
        public readonly int $sourcePosition = 1,
        public readonly int $targetPosition = 1,
        public LineOptionsDto|null $options = null,
        public LineAppearanceDto|null $appearance = null,
    ) {
    }
}
