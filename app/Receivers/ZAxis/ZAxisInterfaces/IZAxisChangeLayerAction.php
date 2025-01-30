<?php

namespace App\Receivers\ZAxis\ZAxisInterfaces;

use App\Models\MnemoSchemaNodeOptions;

interface IZAxisChangeLayerAction extends IZAxisAction
{
    /**
     * Change layer
     * @param array $request
     * @return MnemoSchemaNodeOptions
     */
    public function changeLayer(array $request): MnemoSchemaNodeOptions;
}
