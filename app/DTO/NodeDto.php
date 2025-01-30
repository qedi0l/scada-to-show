<?php

namespace App\DTO;

use App\DTO\Interfaces\DtoInterface;

/**
 * Node DTO
 */
final class NodeDto implements DtoInterface
{
    /**
     * @param string $title
     * @param int $schemaId
     * @param int $groupId
     * @param int $typeId
     * @param NodeOptionsDto|null $options
     * @param NodeAppearanceDto|null $appearance
     * @param NodeGeometryDto|null $geometry
     * @param NodeLinkDto|null $link
     */
    public function __construct(
        public readonly string $title,
        public readonly int $schemaId,
        public readonly int $groupId,
        public readonly int $typeId,
        public NodeOptionsDto|null $options = null,
        public NodeAppearanceDto|null $appearance = null,
        public NodeGeometryDto|null $geometry = null,
        public NodeLinkDto|null $link = null,
    ) {
    }
}
