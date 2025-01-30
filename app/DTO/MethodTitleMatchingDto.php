<?php

namespace App\DTO;

/**
 * Method Title Matching DTO
 */
class MethodTitleMatchingDto
{
    public function __construct(
        public readonly string $frontendMethodTitle,
        public readonly string $receiverTitle,
        public readonly string $concreteCommandTitle,
        public readonly string|null $undoReceiverTitle = null,
    ) {
    }
}
