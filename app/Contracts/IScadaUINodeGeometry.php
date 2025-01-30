<?php

namespace App\Contracts;

use App\Models\MnemoSchemaNode;
use App\Models\MnemoSchemaNodeGeometry;

interface IScadaUINodeGeometry
{
    /**
     * Get node geometry
     * @param MnemoSchemaNode $node
     * @return MnemoSchemaNodeGeometry|null
     */
    public function getNodeGeometry(MnemoSchemaNode $node): MnemoSchemaNodeGeometry|null;
}
