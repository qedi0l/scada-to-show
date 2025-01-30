<?php

namespace App\Services;

use App\Contracts\IScadaUINodeAppearance;
use App\Models\MnemoSchemaNode;
use App\Models\MnemoSchemaNodeAppearance;

class ScadaUINodeAppearanceService implements IScadaUINodeAppearance
{
    /**
     * @param MnemoSchemaNode $node
     * @return MnemoSchemaNodeAppearance|null
     */
    public function getNodeAppearance(MnemoSchemaNode $node): MnemoSchemaNodeAppearance|null
    {
        $appearance = MnemoSchemaNodeAppearance::select(['width', 'height', 'min_svg'])
            ->whereNodeId($node->id)
            ->first();

        if ($appearance === null) {
            return null;
        }

        return new MnemoSchemaNodeAppearance([
            'width' => $appearance->width,
            'height' => $appearance->height,
            'min_svg' => $appearance->min_svg
        ]);
    }
}
