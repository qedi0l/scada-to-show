<?php

namespace App\DTO;

use App\DTO\Interfaces\DtoInterface;

/**
 * Node Type Group Dto
 */
final class NodeTypeGroupDto implements DtoInterface
{
    /**
     * @param string $title
     * @param string|null $description
     * @param string|null $shortTitle
     */
    public function __construct(
        public readonly string $title,
        public readonly string|null $description = null,
        public readonly string|null $shortTitle = null,
    )
    {
    }
}
