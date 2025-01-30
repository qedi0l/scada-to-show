<?php

namespace App\Receivers\SchemaOperation\SchemaOperationInterfaces;

use App\Models\MnemoSchema;

interface ISchemaOperationAddSchemaAction extends ISchemaOperationAction
{
    /**
     * Add schema
     * @param array $request
     * @return MnemoSchema
     */
    public function addSchema(array $request): MnemoSchema;
}
