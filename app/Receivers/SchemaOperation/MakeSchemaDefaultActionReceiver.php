<?php

namespace App\Receivers\SchemaOperation;

use App\Models\MnemoSchema;
use App\Receivers\SchemaOperation\SchemaOperationInterfaces\ISchemaOperationMakeSchemaDefaultAction;
use App\Repositories\SchemaRepository;
use Throwable;

class MakeSchemaDefaultActionReceiver implements ISchemaOperationMakeSchemaDefaultAction
{

    /**
     * @param array $request
     * @return MnemoSchema|null
     * @throws Throwable
     */
    public function makeSchemaDefault(array $request): ?MnemoSchema
    {
        $schemaRepository = new SchemaRepository();

        $schema = $schemaRepository->getByName($request['data']['schema_name']);
        return $schemaRepository->setDefaultSchema($schema);
    }
}
