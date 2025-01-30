<?php

namespace App\Services;

use App\Contracts\IScadaUI;
use App\Contracts\ISchemaImportAndExport;
use App\DTO\LineAppearanceDto;
use App\DTO\LineDto;
use App\DTO\LineOptionsDto;
use App\DTO\NodeAppearanceDto;
use App\DTO\NodeDto;
use App\DTO\NodeGeometryDto;
use App\DTO\NodeOptionsDto;
use App\DTO\SchemaDto;
use App\Models\MnemoSchema;
use App\Repositories\LineRepository;
use App\Repositories\NodeRepository;
use App\Repositories\SchemaRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Throwable;

class MnemoSchemaImportAndExportService implements ISchemaImportAndExport
{
    protected SchemaRepository $schemaRepository;
    protected NodeRepository $nodeRepository;
    protected LineRepository $lineRepository;

    public function __construct()
    {
        $this->schemaRepository = new SchemaRepository();
        $this->nodeRepository = new NodeRepository();
        $this->lineRepository = new LineRepository();
    }

    /**
     * @param string $schemaName
     * @return BinaryFileResponse|JsonResponse
     */
    public function export(string $schemaName): BinaryFileResponse|JsonResponse
    {
        $service = App::make(IScadaUI::class);

        $schema = $this->schemaRepository->getByName($schemaName);

        $schemaData = $service->getDataBySchemaID($schema);

        $jsonData = json_encode($schemaData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        $fileName = "$schemaName.json";
        $relativeFilePath = "schemas/$fileName";

        Storage::disk('local')->put($relativeFilePath, $jsonData);

        $absoluteFilePath = storage_path("app/$relativeFilePath");

        return response()
            ->download(
                $absoluteFilePath,
                $fileName,
                [
                    'Content-type' => 'application/json',
                ]
            )
            ->deleteFileAfterSend();
    }


    /**
     * @param Request $request
     * @return void
     * @throws \Exception
     */
    public function import(Request $request): void
    {
        $content = $request->file('file')->getContent();
        $array = json_decode($content);
        $schemaData = $array->Schema;

        $schema = $this->createSchema($schemaData);

        $nodes = $schemaData->nodes;
        $nodeIdMap = [];

        foreach ($nodes as $nodeData) {

            if (isset($nodeData->options)) {
                $optionData = $nodeData->options;

                $optionsDto = new NodeOptionsDto(
                    nodeId: 0,
                    zIndex: $optionData->z_index ?? 0,
                    parameterCode: $optionData->parameter_code ?? null,
                    hardwareCode: $optionData->hardware_code ?? null,
                    parentId: $optionData->parent_id ?? null,
                    label: $optionData->label ?? null,
                );

                $geometryDto = !isset($optionData->geometry)
                    ? null
                    : new NodeGeometryDto(
                        nodeId: 0,
                        x: $optionData->geometry->x ?? 0,
                        y: $optionData->geometry->y ?? 0,
                        rotation: $optionData->geometry->rotation ?? 0,
                    );

                $appearanceDto = !isset($optionData->appearance)
                    ? null
                    : new NodeAppearanceDto(
                        nodeId: 0,
                        width: $optionData->appearance->width ?? 0,
                        height: $optionData->appearance->height ?? 0,
                        svgUrl: $optionData->appearance->svg_url ?? 'null',
                        minSvg: $optionData->appearance->min_svg ?? 'null',
                    );
            }

            $nodeDto = new NodeDto(
                title: $nodeData->title,
                schemaId: $schema->getKey(),
                groupId: $nodeData->group_id ?? 1,
                typeId: $nodeData->type_id,
                options: $optionsDto ?? null,
                appearance: $appearanceDto ?? null,
                geometry: $geometryDto ?? null,
            );
            $node = $this->nodeRepository->store($nodeDto);

            $nodeIdMap[$nodeData->id] = $node->getKey();

        }

        $typeMapping = [
            'solid' => 1,
            'dashed' => 2,
        ];

        if (isset($schemaData->lines)) {
            foreach ($schemaData->lines as $lineData) {
                $lineOptions = !isset($lineData->options)
                    ? null
                    : new LineOptionsDto(
                        lineId: 0,
                        text: $lineData->options->label ?? null,
                        typeId: $typeMapping[$lineData->options->type] ?? 1,
                        firstArrow: $lineData->options->first_arrow ?? null,
                        secondArrow: $lineData->options->second_arrow ?? null,
                    );

                $lineAppearance = !isset($lineData->options->appearance)
                    ? null
                    : new LineAppearanceDto(
                        lineId: 0,
                        color: $lineData->options->appearance->color ?? '#06B6D4',
                        opacity: $lineData->options->appearance->opacity ?? 100,
                        width: $lineData->options->appearance->width ?? 1,
                    );

                $lineDto = new LineDto(
                    schemaId: $schema->getKey(),
                    firstNodeId: $nodeIdMap[$lineData->first_node] ?? null,
                    secondNodeId: $nodeIdMap[$lineData->second_node] ?? null,
                    sourcePosition: $lineData->first_position ?? 1,
                    targetPosition: $lineData->second_position ?? 1,
                    options: $lineOptions,
                    appearance: $lineAppearance,
                );
                $this->lineRepository->store($lineDto);
            }
        }
    }

    /**
     * @param object $schemaData
     * @return MnemoSchema
     * @throws \Exception
     */
    private function createSchema(object $schemaData): MnemoSchema
    {
        $schemaDto = new SchemaDto(
            name: $schemaData->name,
            title: $schemaData->title,
            projectId: $schemaData->project_id ?? 1,
            isActive: $schemaData->is_active ?? true,
            default: $schemaData->default ?? false
        );

        return $this->schemaRepository->store($schemaDto);
    }


}
