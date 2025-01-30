<?php

namespace App\DTO;

use App\DTO\Interfaces\DtoInterface;

/**
 * Node Link DTO
 */
final class NodeLinkDto implements DtoInterface
{
    public function __construct(
        public int $nodeId,
        public readonly int $schemaId,
    ) {
    }
}
