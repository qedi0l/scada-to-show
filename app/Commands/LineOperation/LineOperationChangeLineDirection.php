<?php

namespace App\Commands\LineOperation;

use App\Commands\AbstractCommand;
use App\DTO\LineOptionsDto;
use App\Receivers\LineOperation\ChangeLineDirectionReceiver;
use App\Repositories\LineOptionsRepository;
use App\Repositories\LineRepository;
use Exception;

class LineOperationChangeLineDirection extends AbstractCommand
{
    /**
     * Change line direction
     * @return void
     * @throws Exception
     */
    public function execute(): void
    {
        // Validate
        $this->request->validate([
            'data.line_id' => 'required|integer',
            'data.first_arrow' => 'string|nullable',
            'data.second_arrow' => 'string|nullable'
        ]);
        $lineId = $this->request->input('data.line_id');

        // Define Schema
        $lineRepository = new LineRepository();
        $line = $lineRepository->getById($lineId);
        $this->setSchemaId($line->schema_id);

        // Execute
        $lineOptionsRepository = new LineOptionsRepository();
        $lineOptions = $lineOptionsRepository->getByLineId($lineId);

        $receiver = new ChangeLineDirectionReceiver();
        $responseData = $receiver->changeLineDirection($this->request->toArray());

        // Set Changes and Response Data
        $this
            ->setChanges([
                'line_id' => $lineId,
                'original_data' => $lineOptions->only(['first_arrow', 'second_arrow', 'line_id']),
                'new_data' => $this->request->input('data')
            ])
            ->setResponseData($responseData);
    }

    /**
     * Undo changing of line direction
     * @return void
     * @throws Exception
     */
    public function undo(): void
    {
        // Validate
        $this->request->validate([
            'original_data' => ['required', 'array'],
            'original_data.line_id' => ['required', 'integer'],
            'original_data.first_arrow' => ['nullable', 'integer'],
            'original_data.second_arrow' => ['nullable', 'integer'],
        ]);
        $originalData = $this->request->input('original_data');

        // Define Schema
        $lineRepository = new LineRepository();
        $line = $lineRepository->getById($originalData['line_id']);
        $this->setSchemaId($line->schema_id);

        // Execute
        $lineOptionsRepository = new LineOptionsRepository();
        $lineOptions = $lineOptionsRepository->getByLineId($originalData['line_id']);
        $dto = new LineOptionsDto(
            lineId: $lineOptions->line_id,
            text: $lineOptions->text,
            typeId: $lineOptions->type_id,
            firstArrow: $originalData['first_arrow'],
            secondArrow: $originalData['second_arrow'],
        );
        $lineOptionsRepository->update($lineOptions, $dto);
    }
}
