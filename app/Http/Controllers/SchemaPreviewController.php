<?php

namespace App\Http\Controllers;

use App\Http\Requests\SchemaPreviewStoreRequest;
use App\Http\Resources\Schemas\MnemoSchemaSimpleResource;
use App\Repositories\SchemaRepository;
use Exception;

/**
 * Schema Preview Controller
 */
class SchemaPreviewController extends Controller
{
    public function __construct(protected SchemaRepository $schemaRepository)
    {
    }

    /**
     * Set Schema Preview
     *
     * @param string $schemaName
     * @param SchemaPreviewStoreRequest $request
     * @return MnemoSchemaSimpleResource
     * @throws Exception
     */
    public function store(string $schemaName, SchemaPreviewStoreRequest $request): MnemoSchemaSimpleResource
    {
        $schema = $this->schemaRepository->getByName($schemaName);

        $schema = $this->schemaRepository->setPreview($schema, $request->file('preview'));

        return MnemoSchemaSimpleResource::make($schema);
    }

    /**
     * Unset Schema Preview
     *
     * @param string $schemaName
     * @return MnemoSchemaSimpleResource
     * @throws Exception
     */
    public function destroy(string $schemaName): MnemoSchemaSimpleResource
    {
        $schema = $this->schemaRepository->getByName($schemaName);

        $schema = $this->schemaRepository->unsetPreview($schema);

        return MnemoSchemaSimpleResource::make($schema);
    }
}
