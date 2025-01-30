<?php

namespace App\Receivers\LineOperation\LineOperationInterfaces;

use App\Models\MnemoSchemaLineAppearance;

interface ILineOperationChangeLineAppearancesAction extends ILineOperationAction
{
    /**
     * Changes line appearance
     * @param array $request
     * @return MnemoSchemaLineAppearance
     */
    public function changeLineAppearances(array $request): MnemoSchemaLineAppearance;
}
