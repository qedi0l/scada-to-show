<?php

namespace App\Receivers\LineOperation;

use App\DTO\LineAppearanceDto;
use App\Models\MnemoSchemaLineAppearance;
use App\Receivers\LineOperation\LineOperationInterfaces\ILineOperationChangeLineAppearancesAction;
use App\Repositories\LineAppearanceRepository;

class ChangeLineAppearancesReceiver implements ILineOperationChangeLineAppearancesAction
{

    protected LineAppearanceRepository $lineAppearanceRepository;

    public function __construct()
    {
        $this->lineAppearanceRepository = new LineAppearanceRepository();
    }

    /**
     * @param array $request
     * @return MnemoSchemaLineAppearance
     */
    public function changeLineAppearances(array $request): MnemoSchemaLineAppearance
    {
        $requestData = $request['data'];

        $lineAppearance = $this->lineAppearanceRepository->getByLineId($requestData['line_id']);

        $dto = new LineAppearanceDTO(
            lineId: $lineAppearance->line_id,
            color: $requestData['color'] ?? $lineAppearance->color,
            opacity: $requestData['opacity'] ?? $lineAppearance->opacity,
            width: $requestData['width'] ?? $lineAppearance->width,
        );
        return $this->lineAppearanceRepository->update($lineAppearance, $dto);
    }
}
