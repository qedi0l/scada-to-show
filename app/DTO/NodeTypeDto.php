<?php

namespace App\DTO;

use App\DTO\Interfaces\DtoInterface;

/**
 * Node Type DTO
 */
final class NodeTypeDto implements DtoInterface
{
    public function __construct(
        public readonly string $type,
        public readonly string $hardwareType,
        public readonly string $svg,
        public readonly int $nodeTypeGroupId,
        public readonly string|null $title = null,
        public readonly bool $serviceType = false,
    ) {
    }
}
