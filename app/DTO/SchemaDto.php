<?php

namespace App\DTO;

use App\DTO\Interfaces\DtoInterface;

/**
 * Schema DTO
 */
final class SchemaDto implements DtoInterface
{
    public function __construct(
        public readonly string $name,
        public readonly string $title,
        public readonly int|null $projectId = null,
        public readonly bool $isActive = true,
        public readonly bool $default = false,
        public readonly string|null $previewFileName = null,
    )
    {
    }
}
