<?php

namespace App\Repositories\Filters;

use Illuminate\Support\Arr;

class NodeCommandFilter implements FilterInterface
{
    public int|null $nodeId = null;

    public int|null $parameterCode = null;

    public array|null $parameterCodesNotIn = null;

    /**
     * @param int $nodeId
     * @return $this
     */
    public function setNodeId(int $nodeId): static
    {
        $this->nodeId = $nodeId;
        return $this;
    }

    /**
     * @param int $parameterCode
     * @return $this
     */
    public function setParameterCode(int $parameterCode): static
    {
        $this->parameterCode = $parameterCode;
        return $this;
    }

    /**
     * @param int|array $parameterCodesNotIn
     * @return $this
     */
    public function setParameterCodesNotIn(int|array $parameterCodesNotIn): static
    {
        $this->parameterCodesNotIn = Arr::wrap($parameterCodesNotIn);
        return $this;
    }
}
