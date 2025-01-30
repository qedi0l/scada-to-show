<?php

namespace App\DTO;

use App\DTO\Interfaces\DtoInterface;

/**
 * Project DTO
 */
final class ProjectDto implements DtoInterface
{
    public function __construct(
        public readonly string $title,
        public readonly string|null $shortTitle = null,
        public readonly string|null $description = null,
    ) {
    }
}
