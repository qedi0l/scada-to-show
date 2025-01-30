<?php

namespace App\Contracts;

use App\Models\MnemoSchema;

interface IScadaUILine
{
    /**
     * Get lines data array for SCADA UI
     * @param MnemoSchema $schema
     * @return array
     */
    public function getLinesBySchema(MnemoSchema $schema): array;

}
