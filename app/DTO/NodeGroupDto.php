<?php

namespace App\DTO;

use App\DTO\Interfaces\DtoInterface;

/**
 * Node Group DTO
 */
final class NodeGroupDto implements DtoInterface
{
    public function __construct(
        public readonly string $title,
        public readonly string $svgUrl,
    ) {
    }
}
