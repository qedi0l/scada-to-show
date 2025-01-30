<?php

namespace App\Receivers\SchemaOperation\SchemaOperationInterfaces;

use App\Models\MnemoSchema;

interface ISchemaOperationMakeSchemaDefaultAction extends ISchemaOperationAction
{
    /**
     * Make schema default
     * @param array $request
     * @return MnemoSchema|null
     */
    public function makeSchemaDefault(array $request): ?MnemoSchema;
}
