<?php

namespace App\DTO;

use App\DTO\Interfaces\DtoInterface;

/**
 * Command Queue DTO
 */
final class CommandQueueDto implements DtoInterface
{
    public function __construct(
        public readonly string $receiverTitle,
        public readonly string $commandTitle,
        public readonly array $commandArray,
        public readonly string|null $schemaId = null,
    ) {
    }
}
