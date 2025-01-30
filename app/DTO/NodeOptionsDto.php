<?php

namespace App\DTO;

use App\DTO\Interfaces\DtoInterface;

/**
 * Node Options DTO
 */
final class NodeOptionsDto implements DtoInterface
{
    public function __construct(
        public int $nodeId,
        public readonly int $zIndex = 0,
        public readonly int|null $parameterCode = null,
        public readonly int|null $hardwareCode = null,
        public readonly int|null $parentId = null,
        public readonly string|null $label = null,
    ) {
    }
}
