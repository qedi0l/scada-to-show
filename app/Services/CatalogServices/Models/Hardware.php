<?php

namespace App\Services\CatalogServices\Models;

readonly class Hardware
{
    public function __construct(
        public string $id,
        public bool $isHasChildren,
        public string $title,
        public string $shortTitle,
        public ?string $childQuery,
        public ?string $parentId,
        public ?string $type,
    )
    {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'isHasChildren' => $this->isHasChildren,
            'title' => $this->title,
            'shortTitle' => $this->shortTitle,
            'childQuery' => $this->childQuery,
            'parentId' => $this->parentId,
            'type' => $this->type,
        ];
    }
}
