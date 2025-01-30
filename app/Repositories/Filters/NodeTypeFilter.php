<?php

namespace App\Repositories\Filters;

class NodeTypeFilter implements FilterInterface
{
    public bool|null $svgNotNull = null;

    public string|null $type = null;

    /**
     * @param bool $svgNotNull
     * @return $this
     */
    public function setSvgNotNull(bool $svgNotNull): static
    {
        $this->svgNotNull = $svgNotNull;
        return $this;
    }

    /**
     * @param string $type
     * @return $this
     */
    public function setType(string $type): static
    {
        $this->type = $type;
        return $this;
    }
}
