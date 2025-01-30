<?php

namespace App\Repositories\Filters;

/**
 * Node Type Group Filters
 */
class NodeTypeGroupFilter implements FilterInterface
{
    /** @var bool|null select by Has Related Node Types with Service Type flag */
    public bool|null $hasServiceTypeNodes = null;

    /**
     * @param bool $hasServiceTypeNodes
     * @return $this
     */
    public function setHasServiceTypeNodes(bool $hasServiceTypeNodes): static
    {
        $this->hasServiceTypeNodes = $hasServiceTypeNodes;
        return $this;
    }
}
