<?php

namespace App\Repositories\Filters;

/**
 * Line Filter
 */
class LineFilter implements FilterInterface
{
    public int|null $schemaId = null;

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
