<?php

namespace App\Receivers\LineOperation\LineOperationInterfaces;

use App\Models\MnemoSchemaLine;

interface ILineOperationAddLineAction extends ILineOperationAction
{
    /**
     * Adds line
     * @param array $request
     * @return MnemoSchemaLine
     */
    public function addLine(array $request): MnemoSchemaLine;
}
