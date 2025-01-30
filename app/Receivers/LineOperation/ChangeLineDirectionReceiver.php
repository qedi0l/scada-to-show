<?php

namespace App\Receivers\LineOperation;

use App\DTO\LineOptionsDto;
use App\Receivers\LineOperation\LineOperationInterfaces\ILineOperationChangeLineDirectionAction;
use App\Repositories\LineArrowTypeRepository;
use App\Repositories\LineOptionsRepository;
use Illuminate\Http\JsonResponse;

class ChangeLineDirectionReceiver implements ILineOperationChangeLineDirectionAction
{
    protected LineOptionsRepository $lineOptionsRepository;

    protected LineArrowTypeRepository $lineArrowTypeRepository;

    public function __construct()
    {
        $this->lineOptionsRepository = new LineOptionsRepository();
        $this->lineArrowTypeRepository = new LineArrowTypeRepository();
    }

    /**
     * @param array $request
     * @return JsonResponse
     */
    public function changeLineDirection(array $request): JsonResponse
    {
        $requestData = $request['data'];

        $firstArrowId = $this->getArrowType($requestData['first_arrow']);
        $secondArrowId = $this->getArrowType($requestData['second_arrow']);

        $lineOptions = $this->lineOptionsRepository->getByLineId($requestData['line_id']);
        $dto = new LineOptionsDto(
            lineId: $lineOptions->line_id,
            text: $lineOptions->text,
            typeId: $lineOptions->type_id,
            firstArrow: $firstArrowId,
            secondArrow: $secondArrowId,
        );
        $this->lineOptionsRepository->update($lineOptions, $dto);

        return response()->json(null);
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
