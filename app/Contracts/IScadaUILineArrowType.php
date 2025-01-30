<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface IScadaUILineArrowType
{
    /**
     * Get available line arrow types
     * @return Collection
     */
    public static function getLineArrowTypes(): Collection;
}
