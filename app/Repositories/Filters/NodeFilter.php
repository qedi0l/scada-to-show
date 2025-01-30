<?php

namespace App\Repositories\Filters;

class NodeFilter implements FilterInterface
{
    public int|null $schemaId = null;

    public string|null $schemaName = null;

    public int|null $parentNodeId = null;

    public int|array|null $hasParameterCodes = null;

    public int|array|null $hasNoParameterCodes = null;

    public bool|null $hasNotEmptyParameterCode = null;

    public int|null $typeId = null;

    /**
     * @param int $schemaId
     * @return $this
     */
    public function setSchemaId(int $schemaId): static
    {
        $this->schemaId = $schemaId;
        return $this;
    }

    /**
     * @param string $schemaName
     * @return $this
     */
    public function setSchemaName(string $schemaName): static
    {
        $this->schemaName = $schemaName;
        return $this;
    }

    /**
     * @param int $parentNodeId
     * @return $this
     */
    public function setParentNodeId(int $parentNodeId): static
    {
        $this->parentNodeId = $parentNodeId;
        return $this;
    }

    /**
     * @param int|array $parameterCodes
     * @return $this
     */
    public function setHasParameterCodes(int|array $parameterCodes): static
    {
        $this->hasParameterCodes = $parameterCodes;
        return $this;
    }

    /**
     * @param int|array $parameterCodes
     * @return $this
     */
    public function setHasNoParameterCodes(int|array $parameterCodes): static
    {
        $this->hasNoParameterCodes = $parameterCodes;
        return $this;
    }

    /**
     * @param bool $value
     * @return $this
     */
    public function setHasNotEmptyParameterCode(bool $value): static
    {
        $this->hasNotEmptyParameterCode = $value;
        return $this;
    }

    public function setTypeId(string $typeId): static
    {
        $this->typeId = $typeId;
        return $this;
    }
}
