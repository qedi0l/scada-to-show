<?php

namespace App\Services;

use App\Contracts\ICatalogService;
use App\Contracts\IScadaUILibrary;
use App\Repositories\NodeTypeRepository;
use App\Services\CatalogServices\Models\Hardware;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ScadaUILibraryService implements IScadaUILibrary
{
    private string $baseUrl;

    public ICatalogService $catalogService;
    private const DEFAULT_TYPE = 'default';

    public function __construct()
    {
        $this->catalogService = App::make(ICatalogService::class);
        $this->baseUrl = config('catalog.base_url');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getLibrary(Request $request): JsonResponse
    {
        $requestedURL = $request->query('url');
        $baseUrl = $this->baseUrl;
        $fullUrl = $baseUrl . $requestedURL;
        $entities = Http::get($fullUrl)->json(['entities']);

        if (Str::startsWith($requestedURL, '/tech-parameters/hardware/')) {
            $hardwares = Http::get($fullUrl)->json(['entities']);

            $filteredHardwares = [];

            foreach ($hardwares as $item) {
                $filteredHardwares[] = [
                    'title' => $item['title'] ?? null,
                    'queryParam' => $item['queryParam'] ?? null,
                    'description' => $item['description'] ?? null,
                    'min_svg' => $item['min_svg'] ?? null
                ];
            }


            return response()->json($filteredHardwares);
        }

        return response()->json($entities);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getHierarchyLibrary(Request $request): JsonResponse
    {
        $queryParam = $request->query("child_query");

        $entities = $this->catalogService->getHardwareHierarchy($queryParam);

        $types = (new NodeTypeRepository())->index()->toArray();

        $result = [];

        /** @var Hardware $entity */
        foreach ($entities as $entity) {
            $currentEntity = $entity->toArray();
            if ($entity->type) {
                $key = array_search($entity->type, array_column($types, "hardware_type"));
                $currentEntity['hardware_type'] = $entity->type;
                $currentEntity['node_type_group_id'] = $types[$key]['node_type_group_id'];
                $currentEntity['type'] = self::DEFAULT_TYPE;
                if ($key !== false) {
                    $currentEntity['type'] = $types[$key]['type'];
                    $currentEntity['node_type_group_id'] = $types[$key]['node_type_group_id'];
                }
            }
            $result[] = $currentEntity;
        }
        return response()->json($result);
    }
}
