<?php

namespace App\Services;

use App\Contracts\IScadaUILineArrowType;
use App\Models\MnemoSchemaLineArrowType;
use Illuminate\Database\Eloquent\Collection;

class ScadaUILineArrowTypeService implements IScadaUILineArrowType
{

    /**
     * @return Collection
     */
    public static function getLineArrowTypes(): Collection
    {
        return MnemoSchemaLineArrowType::all(['id', 'arrow_type_title', 'arrow_type_label']);
    }
}
