<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface IScadaUILineType
{
    /**
     * Get available line types
     * @return Collection
     */
    public static function getLineTypes(): Collection;
}
