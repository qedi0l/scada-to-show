<?php

namespace App\Receivers\SchemaOperation;

use App\DTO\SchemaDto;
use App\Models\MnemoSchema;
use App\Receivers\SchemaOperation\SchemaOperationInterfaces\ISchemaOperationChangeSchemaTitleAction;
use App\Repositories\SchemaRepository;

class ChangeSchemaTitleReceiver implements ISchemaOperationChangeSchemaTitleAction
{
    protected SchemaRepository $schemaRepository;

    public function __construct()
    {
        $this->schemaRepository = new SchemaRepository();
    }

    /**
     * @param array $request
     * @return MnemoSchema
     */
    public function changeSchemaTitle(array $request): MnemoSchema
    {
        $requestData = $request['data'];

        $schema = $this->schemaRepository->getByName($requestData['schema_name']);

        $dto = new SchemaDto(
            name: $schema->name,
            title: $requestData['schema_title'],
            projectId: $schema->project_id,
            isActive: $schema->is_active,
            default: $schema->default,
        );
        return $this->schemaRepository->update($schema, $dto);
    }

}
