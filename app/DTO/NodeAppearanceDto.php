<?php

namespace App\DTO;

use App\DTO\Interfaces\DtoInterface;

/**
 * Node Appearance Dto
 */
final class NodeAppearanceDto implements DtoInterface
{
    public function __construct(
        public int $nodeId,
        public readonly int $width = 100,
        public readonly int $height = 100,
        public readonly string $svgUrl = 'url',
        public readonly string $minSvg = 'url',
    ) {
    }
}
