<?php

namespace App\Receivers\NodeOperation\NodeOperationInterfaces;

use App\Models\MnemoSchemaNodeGeometry;

interface INodeOperationChangeNodeGeometryAction extends INodeOperationAction
{
    /**
     * Change node geometry
     * @param array $request
     * @return MnemoSchemaNodeGeometry
     */
    public function changeNodeGeometry(array $request): MnemoSchemaNodeGeometry;
}
