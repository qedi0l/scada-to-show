<?php

namespace App\Http\Controllers;

use App\Contracts\ISchemaImportAndExport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Schema Export Controller
 */
class MnemoSchemaExportController extends Controller
{
    private ISchemaImportAndExport $schemaImportAndExportService;

    public function __construct()
    {
        $this->schemaImportAndExportService = App::make(ISchemaImportAndExport::class);
    }

    /**
     * Export schema in JSON file
     * @param string $schemaName
     * @return BinaryFileResponse|JsonResponse
     */
    public function export(string $schemaName): BinaryFileResponse|JsonResponse
    {
        return $this->schemaImportAndExportService->export($schemaName);
    }

    /**
     * Import schema in JSON file
     * @param Request $request
     * @return null
     */
    public function import(Request $request)
    {
        return $this->schemaImportAndExportService->import($request);
    }
}
