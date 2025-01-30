<?php

namespace App\DTO;

use App\DTO\Interfaces\DtoInterface;

/**
 * Node Command DTO
 */
final class NodeCommandDto implements DtoInterface
{
    public function __construct(
        public readonly int $nodeId,
        public readonly int $parameterCode,
    ) {
    }
}
