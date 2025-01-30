<?php

namespace App\Receivers\SchemaOperation;

use App\Receivers\SchemaOperation\SchemaOperationInterfaces\ISchemaOperationDeleteSchemaAction;
use App\Repositories\SchemaRepository;
use Laravel\Octane\Exceptions\DdException;
use Throwable;

class DeleteSchemaReceiver implements ISchemaOperationDeleteSchemaAction
{

    /**
     * @param array $request
     * @return bool|null
     * @throws Throwable
     */
    public function deleteSchema(array $request): ?bool
    {
        $schemaRepository = new SchemaRepository();
        $schema = $schemaRepository->getByName($request['data']['schema_name']);

        return $schemaRepository->destroy($schema);
    }
}
