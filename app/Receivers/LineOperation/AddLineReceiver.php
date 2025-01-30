<?php

namespace App\Receivers\LineOperation;

use App\DTO\LineAppearanceDto;
use App\DTO\LineDto;
use App\DTO\LineOptionsDto;
use App\Models\MnemoSchemaLine;
use App\Receivers\LineOperation\LineOperationInterfaces\ILineOperationAddLineAction;
use App\Repositories\LineRepository;
use App\Repositories\SchemaRepository;
use Throwable;

class AddLineReceiver implements ILineOperationAddLineAction
{
    protected SchemaRepository $schemaRepository;
    protected LineRepository $lineRepository;

    public const DEFAULT_COLOR = '#06B6D4';

    public function __construct()
    {
        $this->schemaRepository = new SchemaRepository();
        $this->lineRepository = new LineRepository();
    }

    /**
     * @param array $request
     * @return MnemoSchemaLine
     * @throws \Exception
     */
    public function addLine(array $request): MnemoSchemaLine
    {
        $lineData = $request['data'];
        $lineOptions = $lineData['options'];
        $lineAppearance = $lineOptions['appearance'];

        $schema = $this->schemaRepository->getByName($lineData['schema_name']);

        $lineDto = new LineDto(
            schemaId: $schema->getKey(),
            firstNodeId: $lineData['first_node'],
            secondNodeId: $lineData['second_node'],
            sourcePosition: $lineData['source_position'],
            targetPosition: $lineData['target_position'],
            options: new LineOptionsDto(
                lineId: 0,
                text: $lineOptions['label'] ?? null,
                typeId: $lineOptions['type_id'] ?? 1,
                firstArrow: $lineOptions['first_arrow'] ?? null,
                secondArrow: $lineOptions['second_arrow'] ?? null,
            ),
            appearance: new LineAppearanceDto(
                lineId: 0,
                color: $lineAppearance['color'] ?? self::DEFAULT_COLOR,
                opacity: $lineAppearance['opacity'] ?? 100,
                width: $lineAppearance['width'] ?? 1,
            ),
        );

        return $this->lineRepository->store($lineDto);
    }
}
