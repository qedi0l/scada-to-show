<?php

namespace App\Receivers\LineOperation\LineOperationInterfaces;

use App\Models\MnemoSchemaLineOptions;

interface ILineOperationChangeLineOptionsAction extends ILineOperationAction
{
    /**
     * Changes line options
     * @param array $request
     * @return MnemoSchemaLineOptions
     */
    public function changeLineOptions(array $request): MnemoSchemaLineOptions;
}
