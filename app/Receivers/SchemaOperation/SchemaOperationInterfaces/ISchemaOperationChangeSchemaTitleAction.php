<?php

namespace App\Receivers\SchemaOperation\SchemaOperationInterfaces;

use App\Models\MnemoSchema;

interface ISchemaOperationChangeSchemaTitleAction extends ISchemaOperationAction
{
    /**
     * Change schema titles
     * @param array $request
     * @return MnemoSchema
     */
    public function changeSchemaTitle(array $request): MnemoSchema;
}
