<?php

namespace App\DTO;

use App\DTO\Interfaces\DtoInterface;

/**
 * Line Arrow Type DTO
 */
final class LineArrowTypeDto implements DtoInterface
{
    public function __construct(
        public readonly string $arrowTypeTitle,
    ) {
    }
}
