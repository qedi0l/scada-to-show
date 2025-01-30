<?php

namespace App\DTO;

use App\DTO\Interfaces\DtoInterface;

/**
 * Node Geometry DTO
 */
final class NodeGeometryDto implements DtoInterface
{
    /**
     * @param int $nodeId
     * @param int|null $x
     * @param int|null $y
     * @param int|null $rotation
     */
    public function __construct(
        public int $nodeId,
        public readonly int|null $x = 0,
        public readonly int|null $y = 0,
        public readonly int|null $rotation = 0,
    ) {
    }
}
