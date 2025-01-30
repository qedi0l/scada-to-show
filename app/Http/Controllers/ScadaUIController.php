<?php

namespace App\Http\Controllers;

use App\Contracts\IScadaSignals;
use App\Http\Resources\Schemas\MnemoSchemaNodeDataResource;
use App\Http\Resources\Schemas\MnemoSchemaResource;
use App\Http\Resources\Schemas\MnemoSchemaSignalsResource;
use App\Http\Resources\Schemas\MnemoSchemaSignalsSingleResource;
use App\Http\Resources\Schemas\MnemoSchemaSimpleResource;
use App\Http\Resources\Schemas\MnemoSchemaTitlesResource;
use App\Http\Resources\Schemas\TitlesResource;
use App\Models\MnemoSchemaNode;
use App\Models\MnemoSchemaNodeAppearance;
use App\Models\MnemoSchemaNodeGeometry;
use App\Models\MnemoSchemaNodeOptions;
use App\Repositories\Filters\SchemaFilter;
use App\Repositories\SchemaRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\App;

class ScadaUIController extends Controller
{
    private IScadaSignals $signals;

    private SchemaRepository $repository;

    public function __construct()
    {
        $this->signals = App::make(IScadaSignals::class);
        $this->repository = new SchemaRepository();
    }

    /**
     * Get data by schema name
     * @param string $schemaName
     * @return JsonResponse
     */
    public function getDataBySchemeName(string $schemaName): JsonResponse
    {
        $schema = $this->repository->getSchemaDataByName($schemaName);

        if (is_null($schema)) {
            abort(404);
        }

        return response()->json(['Schema' => MnemoSchemaResource::make($schema)]);
    }

    /**
     * Get node parameters by schema name
     * @param string $schemaName
     * @return MnemoSchemaNodeDataResource
     */
    public function getNodeParamsBySchemaName(string $schemaName): MnemoSchemaNodeDataResource
    {
        $schema = $this->repository->getNodeParamsByName($schemaName);

        if (is_null($schema)) {
            abort(404);
        }

        return MnemoSchemaNodeDataResource::make($schema);
    }

    /**
     * Get all schemas
     * @return AnonymousResourceCollection
     */
    public function getAllMnemoSchemas(): AnonymousResourceCollection
    {
        $items = $this->repository->getAllSchemasWithData();

        return MnemoSchemaResource::collection($items);
    }

    /**
     * Get signals of all schemas
     * @return MnemoSchemaSignalsResource
     */
    public function getSignalsOfAllSchemas(): MnemoSchemaSignalsResource
    {
        $items = $this->repository->getSchemasWithSignals();

        if ($items->isEmpty()) {
            abort('404');
        }

        return MnemoSchemaSignalsResource::make($items);
    }

    /**
     * Get signals of chosen schema
     * @param string $schemaName
     * @return MnemoSchemaSignalsSingleResource
     */
    public function getSignalsOfSingleSchema(string $schemaName): MnemoSchemaSignalsSingleResource
    {
        $items = $this->repository->getSchemasWithSignals($schemaName);

        if ($items->isEmpty()) {
            abort('404');
        }

        return MnemoSchemaSignalsSingleResource::make($items);
    }

    /**
     * Get titles of all schemas
     * @return TitlesResource
     */
    public function getSchemaTitles(): TitlesResource
    {
        $items = $this->repository->index();

        return TitlesResource::make($items);
    }

    /**
     * Get schemas by project
     * @param int $projectId
     * @return AnonymousResourceCollection
     */
    public function byProject(int $projectId): AnonymousResourceCollection
    {
        $filter = (new SchemaFilter())->setProjectId($projectId);

        $items = $this->repository->index($filter);

        return MnemoSchemaSimpleResource::collection($items);
    }


    /**
     * Get available signals of chosen node
     * @param int $nodeId
     * @return mixed
     */
    public function getNodeAvailableSignals(int $nodeId)
    {
        return response()->json($this->signals->getNodeAvailableSignals($nodeId))->original;
    }
}
