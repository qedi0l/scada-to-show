<?php

namespace App\Repositories\Filters;

/**
 * Node Link Filter
 */
class NodeLinkFilter implements FilterInterface
{
    public int|null $nodeId = null;

    public int|null $schemaId = null;

    /**
     * @param int $nodeId
     * @return $this
     */
    public function setNodeID(int $nodeId): static
    {
        $this->nodeId = $nodeId;
        return $this;
    }

    /**
     * @param int $schemaId
     * @return $this
     */
    public function setSchemaId(int $schemaId): static
    {
        $this->schemaId = $schemaId;
        return $this;
    }
}
