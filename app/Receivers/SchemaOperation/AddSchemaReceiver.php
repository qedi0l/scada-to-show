<?php

namespace App\Receivers\SchemaOperation;

use App\DTO\SchemaDto;
use App\Models\MnemoSchema;
use App\Receivers\SchemaOperation\SchemaOperationInterfaces\ISchemaOperationAddSchemaAction;
use App\Repositories\SchemaRepository;
use Exception;
use Illuminate\Support\Str;

class AddSchemaReceiver implements ISchemaOperationAddSchemaAction
{
    protected SchemaRepository $schemaRepository;

    public function __construct()
    {
        $this->schemaRepository = new SchemaRepository();
    }

    /**
     * @param array $request
     * @return MnemoSchema
     * @throws Exception
     */
    public function addSchema(array $request): MnemoSchema
    {
        $requestData = $request['data'];

        $dto = new SchemaDto(
            $requestData['schema_name'] ?? Str::uuid()->toString(),
            $requestData['schema_title'],
        );

        return $this->schemaRepository->store($dto);
    }
}
