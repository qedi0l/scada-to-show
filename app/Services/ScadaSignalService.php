<?php

namespace App\Services;

use App\Contracts\ICatalogService;
use App\Contracts\IScadaSignals;
use App\Http\Resources\Schemas\MnemoSchemaCommandsSignalsResource;
use App\Models\MnemoSchema;
use App\Models\MnemoSchemaNode;
use App\Models\MnemoSchemaNodeCommand;
use App\Models\MnemoSchemaNodeOptions;
use App\Repositories\NodeOptionsRepository;
use App\Repositories\NodeRepository;
use App\RESTModels\SchemaSignals;
use App\RESTModels\Signal;
use App\Services\CatalogServices\Models\MetaData;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;

class ScadaSignalService implements IScadaSignals
{
    protected NodeRepository $nodeRepository;
    protected NodeOptionsRepository $nodeOptionsRepository;
    protected ICatalogService $catalogService;

    public function __construct()
    {
        $this->nodeRepository = new NodeRepository();
        $this->nodeOptionsRepository = new NodeOptionsRepository();
        $this->catalogService = App::make(ICatalogService::class);
    }

    /**
     * @param int $hardwareCode
     * @param $signalsId
     * @return array
     */
    private function getSignalsByHardwareCode(int $hardwareCode, $signalsId = null): array
    {
        $signalId = is_array($signalsId) ? $signalsId : [];

        $responseWithReadableSignals = $this->catalogService->postMetaData($hardwareCode, $signalId);

        if (is_null($responseWithReadableSignals) || $responseWithReadableSignals->isEmpty()) {
            return [];
        }

        return $responseWithReadableSignals
            ->map(function (MetaData $metaData) {
                return new Signal(
                    $metaData->signalData->name,
                    $metaData->signalId,
                    $metaData->hardwareId,
                    $metaData->signalData?->label
                );
            })
            ->toArray();
    }

    public function getNodeAvailableSignals(int $nodeId): JsonResponse
    {
        try {
            $nodeOptions = $this->nodeOptionsRepository->getByNodeId($nodeId);
        } catch (Exception) {
            return response()->json(null);
        }

        $response = $nodeOptions->hardware_code
            ? $this->getSignalsByHardwareCode($nodeOptions->hardware_code)
            : null;

        return response()->json($response);
    }

    public function getSignalsMetaData(int $nodeId): array
    {
        $nodeOptions = $this->nodeOptionsRepository->getByNodeId($nodeId);

        return $this->catalogService->postMetaData($nodeOptions->hardware_code);
    }
}
