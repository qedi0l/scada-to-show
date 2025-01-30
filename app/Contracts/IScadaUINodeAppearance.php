<?php

namespace App\Contracts;

use App\Models\MnemoSchemaNode;
use App\Models\MnemoSchemaNodeAppearance;

interface IScadaUINodeAppearance
{
    /**
     * Get node appearance
     * @param MnemoSchemaNode $node
     * @return MnemoSchemaNodeAppearance|null
     */
    public function getNodeAppearance(MnemoSchemaNode $node): MnemoSchemaNodeAppearance|null;
}
