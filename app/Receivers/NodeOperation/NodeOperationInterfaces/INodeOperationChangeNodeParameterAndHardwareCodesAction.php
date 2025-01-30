<?php

namespace App\Receivers\NodeOperation\NodeOperationInterfaces;

interface INodeOperationChangeNodeParameterAndHardwareCodesAction extends INodeOperationAction
{
    /**
     * Change node parameter and hardware codes
     * @param array $request
     * @return string
     */
    public function changeNodeParameterAndHardwareCodes(array $request): string;
}
