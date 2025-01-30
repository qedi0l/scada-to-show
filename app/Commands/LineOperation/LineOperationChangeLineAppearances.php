<?php

namespace App\Commands\LineOperation;

use App\Commands\AbstractCommand;
use App\DTO\LineAppearanceDto;
use App\Receivers\LineOperation\ChangeLineAppearancesReceiver;
use App\Repositories\LineAppearanceRepository;
use App\Repositories\LineRepository;
use Exception;

class LineOperationChangeLineAppearances extends AbstractCommand
{
    /**
     * Change line appearance
     * @return void
     * @throws Exception
     */
    public function execute(): void
    {
        // Validate
        $this->request->validate([
            'data' => ['required', 'array'],
            'data.line_id' => ['required', 'integer'],
            'data.color' => ['string'],
            'data.opacity' => ['integer', 'min:0', 'max:100'],
            'data.width' => ['integer', 'min:1', 'max:50']
        ]);

        // Define Schema
        $lineId = $this->request->input('data.line_id');
        $lineRepository = new LineRepository();
        $line = $lineRepository->getById($lineId);
        $this->setSchemaId($line->schema_id);

        // Execute
        $lineAppearanceRepository = new LineAppearanceRepository();
        $lineAppearance = $lineAppearanceRepository->getByLineId($lineId);

        $receiver = new ChangeLineAppearancesReceiver();
        $receiver->changeLineAppearances($this->request->toArray());

        // Set Changes and Response Data
        $this
            ->setChanges([
                'line_id' => $lineId,
                'original_data' => $lineAppearance->toArray(),
                'new_data' => $this->request->input('data')
            ])
            ->setResponseData(null);
    }


    /**
     * Undo changing of line appearances
     * @return void
     * @throws Exception
     */
    public function undo(): void
    {
        // Validate
        $this->request->validate([
            'original_data' => ['required', 'array'],
            'original_data.line_id' => ['required', 'integer'],
            'original_data.color' => ['required', 'string'],
            'original_data.opacity' => ['required', 'integer'],
            'original_data.width' => ['required', 'integer'],
        ]);
        $originalData = $this->request->get('original_data');

        // Define Schema
        $line = (new LineRepository())->getById($originalData['line_id']);
        $this->setSchemaId($line->schema_id);

        // Execute
        $lineAppearanceRepository = new LineAppearanceRepository();
        $lineAppearance = $lineAppearanceRepository->getByLineId($originalData['line_id']);
        $dto = new LineAppearanceDto(
            lineId: $originalData['line_id'],
            color: $originalData['color'],
            opacity: $originalData['opacity'],
            width: $originalData['width'],
        );
        $lineAppearanceRepository->update($lineAppearance, $dto);
    }
}
