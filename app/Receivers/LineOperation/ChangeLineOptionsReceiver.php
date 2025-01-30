<?php

namespace App\Receivers\LineOperation;

use App\DTO\LineOptionsDto;
use App\Models\MnemoSchemaLineOptions;
use App\Receivers\LineOperation\LineOperationInterfaces\ILineOperationChangeLineOptionsAction;
use App\Repositories\LineArrowTypeRepository;
use App\Repositories\LineOptionsRepository;
use App\Repositories\LineRepository;
use App\Repositories\LineTypeRepository;

class ChangeLineOptionsReceiver implements ILineOperationChangeLineOptionsAction
{
    protected LineRepository $lineRepository;
    protected LineOptionsRepository $lineOptionsRepository;

    protected LineTypeRepository $lineTypeRepository;

    protected LineArrowTypeRepository $lineArrowTypeRepository;

    public function __construct()
    {
        $this->lineRepository = new LineRepository();
        $this->lineOptionsRepository = new LineOptionsRepository();
        $this->lineTypeRepository = new LineTypeRepository();
        $this->lineArrowTypeRepository = new LineArrowTypeRepository();
    }

    /**
     * @param array $request
     * @return MnemoSchemaLineOptions
     */
    public function changeLineOptions(array $request): MnemoSchemaLineOptions
    {
        $requestData = $request['data'];

        $lineOptions = $this->lineOptionsRepository->getByLineId($requestData['line_id']);

        $lineTypeId = $this->lineTypeRepository->getByType($requestData['type'])->getKey();

        $dto = new LineOptionsDto(
            lineId: $lineOptions->line_id,
            text: $requestData['label'],
            typeId: $lineTypeId,
            firstArrow: $this->getArrowType($requestData['first_arrow']),
            secondArrow: $this->getArrowType($requestData['second_arrow']),
        );
        return $this->lineOptionsRepository->update($lineOptions, $dto);
    }

    /**
     * Get Arrow Type
     *
     * @param string|null $type
     * @return int|null
     */
    private function getArrowType(string|null $type): ?int
    {
        if (is_null($type)) {
            return null;
        }

        return $this->lineArrowTypeRepository->getByTitle($type)->getKey();
    }
}
