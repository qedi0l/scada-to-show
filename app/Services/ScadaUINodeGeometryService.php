<?php

namespace App\Services;

use App\Contracts\IScadaUINodeGeometry;
use App\Models\MnemoSchemaNode;
use App\Models\MnemoSchemaNodeGeometry;

class ScadaUINodeGeometryService implements IScadaUINodeGeometry
{
    /**
     * @param MnemoSchemaNode $node
     * @return MnemoSchemaNodeGeometry|null
     */
    public function getNodeGeometry(MnemoSchemaNode $node): MnemoSchemaNodeGeometry|null
    {
        $geometry = MnemoSchemaNodeGeometry::select(['x', 'y', 'rotation'])
            ->whereNodeId($node->id)
            ->first();

        if ($geometry === null) {
            return null;
        }

        return new MnemoSchemaNodeGeometry([
            'x' => $geometry->x,
            'y' => $geometry->y,
            'rotate' => $geometry->rotation,
        ]);
    }
}
