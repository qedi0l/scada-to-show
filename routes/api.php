<?php

use App\Http\Controllers\GeneralEditorController;
use App\Http\Controllers\MethodTitleMatchingController;
use App\Http\Controllers\MnemoSchemaExportController;
use App\Http\Controllers\MnemoSchemaNodeController;
use App\Http\Controllers\MnemoSchemaNodeTypeController;
use App\Http\Controllers\NodeTypeGroupController;
use App\Http\Controllers\RoutesInfoController;
use App\Http\Controllers\ScadaUIController;
use App\Http\Controllers\ScadaUILibraryController;
use App\Http\Controllers\ScadaUILineArrowTypeController;
use App\Http\Controllers\ScadaUILineTypeController;
use App\Http\Controllers\ScadaUiNodeTypeController;
use App\Http\Controllers\ScadaUIProjectController;
use App\Http\Controllers\SchemaPreviewController;
use App\Http\Controllers\ToolbarViewerController;
use App\Tools\NodeImporter;
use Illuminate\Support\Facades\Route;


/** Routes Info */
Route::get('routes', RoutesInfoController::class);

Route::prefix('/v1/scada/ui')->group(function () {

    Route::get('/export/{schemaName}', [MnemoSchemaExportController::class, 'export']);
    Route::post('/import', [MnemoSchemaExportController::class, 'import']);

    Route::prefix('get')->group(function () {
        Route::get('data/{schemaName}', [ScadaUIController::class, 'getDataBySchemeName']);
        Route::get('nodeData/{schemaName}', [ScadaUIController::class, 'getNodeParamsBySchemaName']);
        Route::get('all', [ScadaUIController::class, 'getAllMnemoSchemas']);
        Route::get('signals', [ScadaUIController::class, 'getSignalsOfAllSchemas']);
        Route::get('signals/{schemaName}', [ScadaUIController::class, 'getSignalsOfSingleSchema']);
        Route::get('schema/titles', [ScadaUIController::class, 'getSchemaTitles']);

        Route::get('projects', [ScadaUIProjectController::class, 'index']);
        Route::get('schemasByProject/{projectId}', [ScadaUIController::class, 'byProject']);

        Route::get('nodeHierarchy/{schemaName}/{nodeId}', [MnemoSchemaNodeController::class, 'showHierarchyBySchema']);
        Route::get('library', [ScadaUILibraryController::class, 'getLibrary']);
        Route::get('node/commands/{nodeId}', [ToolbarViewerController::class, 'availableCommands']);

        Route::get('node/types', [MnemoSchemaNodeTypeController::class, 'index']);

        Route::get('command/names', [MethodTitleMatchingController::class, 'names']);


        Route::get('node/all/commands/{nodeId}', [ToolbarViewerController::class, 'getAllCommands']);
        Route::get('node/available/signals/{nodeId}', [ScadaUIController::class, 'getNodeAvailableSignals']);
        Route::get('child/nodes/{nodeId}', [MnemoSchemaNodeController::class, 'getChildNodes']);
        Route::get('line/type', [ScadaUILineTypeController::class, 'index']);
        Route::get('line/arrow/type', [ScadaUILineArrowTypeController::class, 'index']);

    });

    Route::post('schema/{schemaName}/preview', [SchemaPreviewController::class, 'store']);
    Route::delete('schema/{schemaName}/preview', [SchemaPreviewController::class, 'destroy']);

//    Route::put('/update/{schemaName}', [MnemoSchemaController::class, 'update']);

    /* Node Type Groups */
    Route::get('/get/node/type/groups', [NodeTypeGroupController::class, 'index']);
    Route::post('/create/node/type/group', [NodeTypeGroupController::class, 'store']);
    Route::put('/update/node/type/group', [NodeTypeGroupController::class, 'update']);
    Route::delete('/delete/node/type/group', [NodeTypeGroupController::class, 'destroy']);

    /* Node Types */
    Route::get('/get/node/all/types', [ScadaUiNodeTypeController::class, 'index']);
    Route::post('/create/node/type', [ScadaUiNodeTypeController::class, 'store']);
    Route::put('/update/node/type', [ScadaUiNodeTypeController::class, 'update']);
    Route::delete('/delete/node/type/{nodeTypeId}', [ScadaUiNodeTypeController::class, 'destroy']);

    Route::post('/execute/command', [GeneralEditorController::class, 'execute']);
    Route::post('/undo/last/command/{schemaName}', [GeneralEditorController::class, 'undo']);

    /* Service Routes */
    Route::post('/tools/node/import', [NodeImporter::class, 'import']);
});
