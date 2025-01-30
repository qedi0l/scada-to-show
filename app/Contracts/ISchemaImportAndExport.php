<?php

namespace App\Contracts;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

interface ISchemaImportAndExport
{
    /**
     * Export JSON file with schema
     * @param string $schemaName
     * @return BinaryFileResponse|JsonResponse
     */
    public function export(string $schemaName): BinaryFileResponse|JsonResponse;

    /**
     * Uploads schema with JSON file
     * @param Request $request
     * @return void
     */
    public function import(Request $request): void;
}
