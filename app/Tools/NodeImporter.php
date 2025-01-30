<?php

namespace App\Tools;

use App\DTO\LineAppearanceDto;
use App\DTO\LineDto;
use App\DTO\LineOptionsDto;
use App\DTO\NodeAppearanceDto;
use App\DTO\NodeDto;
use App\DTO\NodeGeometryDto;
use App\DTO\NodeOptionsDto;
use App\DTO\SchemaDto;
use App\Exceptions\LineAlreadyExistsException;
use App\Repositories\LineRepository;
use App\Repositories\NodeRepository;
use App\Repositories\NodeTypeRepository;
use App\Repositories\SchemaRepository;
use App\Tools\Service\NodeImportCnfg;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class NodeImporter
{
    protected SchemaRepository $schemaRepository;
    protected NodeRepository $nodeRepository;
    protected LineRepository $lineRepository;

    private array $data;
    private Request $request;
    private string $schemaId;
    private array $nodeIdTransform = [];
    private array $lineIdTransform = [];

    public function __construct()
    {
        $this->schemaRepository = new SchemaRepository();
        $this->nodeRepository = new NodeRepository();
        $this->lineRepository = new LineRepository();
    }

    /**
     * @throws Throwable
     */
    public function import(Request $request): JsonResponse
    {
        $this->request = $request;
        if ($request->hasFile('file')) {
            $this->setData($request);
            $this->handleFile();

            return response()->json(['import_status' => 'successful']);
        }
        return response()->json(['import_status' => 'file not found']);
    }


    /**
     * @throws Throwable
     */
    private function handleFile(): void
    {
        $this->createSchema();
        $this->tryHandleNodes();
        $this->tryHandleLines();
    }

    private function setData(Request $request): void
    {
        $file = $request->file('file');
        $data = new NodeImportCnfg();
        $data->setSheets(['lines', 'nodes']);
        $this->data = $data->toArray($file);
    }

    /**
     * @throws Throwable
     */
    private function tryHandleNodes(): void
    {
        $nodesList = $this->data['nodes'];

        foreach ($nodesList as $node) {
            if ($node[0] == 'Id') {
                continue;
            }
            $this->createNode($node);
        }
    }

    /**
     * @throws Throwable
     */
    private function tryHandleLines(): void
    {
        $lineList = $this->data['lines'];

        foreach ($lineList as $line) {
            if ($line[0] == 'id') {
                continue;
            }
            $this->createLine($line);
        }
    }

    /**
     * @throws Throwable
     */
    private function createNode(array $node): void
    {
        $node['type_id'] = (new NodeTypeRepository())->getByType($node[1])->getKey();

        $nodeDto = new NodeDto(
            title: $node[5],
            schemaId: $this->schemaId,
            groupId: 1,
            typeId: $node['type_id'],
            options: new NodeOptionsDto(
                nodeId: 0,
                zIndex: $node[6],
                parameterCode: $node[2] ?? null,
                hardwareCode: $node[3] ?? null,
                parentId: $this->nodeIdTransform[$node[4]] ?? null,
            ),
            appearance: new NodeAppearanceDto(nodeId: 0),
            geometry: new NodeGeometryDto(nodeId: 0),
        );

        $this->nodeRepository->store($nodeDto);
    }

    /**
     * @return void
     * @throws Exception
     */
    private function createSchema(): void
    {
        $dto = new SchemaDto(
            name: uuid_create(),
            title: $this->request->input('schema_name')
        );

        $schema = $this->schemaRepository->store($dto);

        $this->schemaId = $schema->getKey();
    }

    /**
     * @throws Throwable
     */
    private function createLine(array $line): void
    {
        $lineDto = new LineDto(
            schemaId: $this->schemaId,
            firstNodeId: $this->nodeIdTransform[$line[1]],
            secondNodeId: $this->nodeIdTransform[$line[2]],
            options: new LineOptionsDto(
                lineId: 0,
                text: $lineOptions['label'] ?? null,
            ),
            appearance: new LineAppearanceDto(lineId: 0),
        );
        $createdLineInstance = $this->lineRepository->store($lineDto);

        if (array_key_exists($line[0], $this->lineIdTransform)) {
            throw new LineAlreadyExistsException($line[0]);
        }
        $this->lineIdTransform[$line[0]] = $createdLineInstance->getKey();
    }
}
