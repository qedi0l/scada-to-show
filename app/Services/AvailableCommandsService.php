<?php

namespace App\Services;

use App\Contracts\IAvailableCommands;
use App\Contracts\ICatalogService;
use App\Models\MnemoSchemaNode;
use App\Repositories\NodeRepository;
use App\Services\CatalogServices\Models\Signal;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;


class AvailableCommandsService implements IAvailableCommands
{

    protected NodeRepository $nodeRepository;

    public ICatalogService $catalogService;

    public function __construct()
    {
        $this->nodeRepository = new NodeRepository();
        $this->catalogService = App::make(ICatalogService::class);
    }

    /**
     * @param int $nodeId
     * @return array|null
     * @throws Exception
     */
    public function getCommands(int $nodeId): array|null
    {
        $host = config('catalog.base_url');

        $url = $host . '/api/v1/metrics/signals/metadata';

        $data[] = $this->getRequestBody($nodeId);

        foreach ($data as &$value) {
            if ($value["signalsId"] == null) {
                $value = null;
            }
        }

        $response = Http::post($url, $data);

        if ($response->json() == null) {
            return null;
        }
        $data = [];
        foreach ($response->json() as $item) {
            $data[] = $this->transformCommand($item);
        }
        return $data;
    }

    /**
     * @param int $nodeId
     * @return array
     * @throws Exception
     */
    public function getRequestBody(int $nodeId): array
    {
        $node = $this->nodeRepository->getById($nodeId);
        $node->load(['commands', 'options']);

        return [
            'hardwareId' => $node->options?->hardware_code,
            'signalsId' => $node->commands?->pluck("parameter_code")->toArray(),
        ];
    }

    /**
     * @param int $nodeId
     * @return array|JsonResponse
     * @throws Exception
     */
    public function getAvailableCommands(int $nodeId): array|JsonResponse
    {
        $hardId = $this->nodeRepository->getHardwareCodeByNodeId($nodeId);

        if (!$hardId) {
            return response()->json(['message' => 'hardware not found']);
        }

        $signalsCollection = $this->catalogService->getWritableSignalsByHardwareCode($hardId);

        if (is_null($signalsCollection) || $signalsCollection->isEmpty()) {
            return response()->json(['message' => 'available commands do not exist']);
        }

        return $signalsCollection
            ->map(function (Signal $signal) {
                return [
                    'hardware_code' => $signal->transportHardwareId,
                    'parameter_code' => $signal->transportSignalId,
                    'title' => $signal->title,
                    'signal_type' => $signal->signalType->shortTitle,
                    'measurement_unit' => [
                        'unit' => $signal->measurementUnit?->shortTitle
                    ]
                ];
            })
            ->toArray();
    }

    /**
     * @param array $data
     * @return array|JsonResponse
     */
    public function transform(array $data): array|JsonResponse
    {
        return [
            'hardware_code' => $data['transportHardwareId'] ?? $data['hardwareId'],
            'parameter_code' => $data['transportSignalId'] ?? $data['signalId'],
            'title' => $data['title'] ?? null,
            'signal_type' => $data['signalType']['shortTitle'] ?? null,
            'measurement_unit' => [
                'unit' => $data['measurementUnit']['shortTitle'] ?? null
            ]
        ];
    }

    /**
     * @param array $data
     * @return array|JsonResponse
     */
    public function transformCommand(array $data): array|JsonResponse
    {
        $signalData = $data['signalData'];

        return [
            'hardware_code' => $data['transportHardwareId'] ?? $data['hardwareId'],
            'parameter_code' => $data['transportSignalId'] ?? $data['signalId'],
            'title' => $signalData['name'],
            'signal_type' => $signalData['signalType'],
            'measurement_unit' => [
                'unit' => $signalData['unit']
            ]
        ];
    }
}

