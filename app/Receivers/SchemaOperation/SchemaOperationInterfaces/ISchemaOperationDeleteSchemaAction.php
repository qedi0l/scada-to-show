<?php

namespace App\Receivers\SchemaOperation\SchemaOperationInterfaces;


interface ISchemaOperationDeleteSchemaAction extends ISchemaOperationAction
{
    /**
     * Delete schema
     * @param array $request
     * @return bool|null
     */
    public function deleteSchema(array $request): ?bool;
}
