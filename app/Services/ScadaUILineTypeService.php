<?php

namespace App\Services;

use App\Contracts\IScadaUILineType;
use App\Models\MnemoSchemaLineType;
use Illuminate\Database\Eloquent\Collection;

class ScadaUILineTypeService implements IScadaUILineType
{
    /**
     * @return Collection
     */
    public static function getLineTypes(): Collection
    {
        return MnemoSchemaLineType::all(['id', 'type', 'line_type_label']);
    }
}
