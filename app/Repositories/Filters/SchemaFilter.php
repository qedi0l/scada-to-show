<?php

namespace App\Repositories\Filters;

/**
 * Schema Filters
 */
class SchemaFilter implements FilterInterface
{
    /** @var int|null select by Project ID */
    public ?int $projectId = null;

    /** @var string|null select by Name */
    public ?string $name = null;

    /** @var bool|null select by Active Flag */
    public ?bool $active = null;

    /** @var bool|null select by Default Flag */
    public ?bool $default = null;

    /** @var bool|null select by Has Related Nodes Condition */
    public ?bool $hasNodes = null;

    /** @var bool|null select by Has Related Nodes with no empty Parameter Code */
    public ?bool $hasNotEmptyParameterCodeNodes = null;

    public function setProjectId(int $projectId): static
    {
        $this->projectId = $projectId;
        return $this;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;
        return $this;
    }

    public function setDefault(bool $default): static
    {
        $this->default = $default;
        return $this;
    }

    public function setHasNodes(bool $hasNodes): static
    {
        $this->hasNodes = $hasNodes;
        return $this;
    }

    public function setHasNotEmptyParameterCodeNodes(bool $hasNotEmptyParameterCodeNodes): static
    {
        $this->hasNotEmptyParameterCodeNodes = $hasNotEmptyParameterCodeNodes;
        return $this;
    }
}
