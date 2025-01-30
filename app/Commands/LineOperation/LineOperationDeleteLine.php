<?php

namespace App\Commands\LineOperation;

use App\Commands\AbstractCommand;
use App\Receivers\LineOperation\AddLineReceiver;
use App\Receivers\LineOperation\DeleteLineReceiver;
use App\Repositories\LineRepository;
use App\Repositories\SchemaRepository;
use Exception;
use Illuminate\Http\Request;
use Throwable;

class LineOperationDeleteLine extends AbstractCommand
{
    /**
     * Delete line
     * @return void
     * @throws Exception
     * @throws Throwable
     */
    public function execute(): void
    {
        // Validate
        $this->request->validate([
            'data.line_id' => ['required', 'integer'],
        ]);
        $lineId = $this->request->input('data.line_id');
        $lineRepository = new LineRepository();
        $line = $lineRepository->getById($lineId)->load(['schema', 'options.type', 'appearance']);

        // Define Schema
        $this->setSchemaId($line->schema_id);

        // Execute
        $lineData = [
            'schema_name' => $line->schema->name,
            'first_node' => $line->first_node,
            'second_node' => $line->second_node,
            'source_position' => $line->source_position,
            'target_position' => $line->target_position,
            'options' => [
                'label' => $line->options->text,
                'type' => $line->options->type->type,
                'first_arrow' => $line->options->first_arrow,
                'second_arrow' => $line->options->second_arrow,
                'appearance' => [
                    'color' => $line->appearance->color,
                    'opacity' => $line->appearance->opacity,
                    'width' => $line->appearance->width
                ]
            ]
        ];
        $receiver = new DeleteLineReceiver();
        $receiver->deleteLine($this->request->toArray());

        // Set Changes and Response Data
        $this
            ->setChanges([
                'line_id' => $lineId,
                'line_data' => $lineData
            ])
            ->setResponseData(null);
    }

    /**
     * Undo deletion of line
     * @return void
     * @throws Throwable
     */
    public function undo(): void
    {
        // Validate
        $this->request->validate([
            'line_data' => ['required', 'array'],
        ]);
        $lineData = $this->request->input('line_data');

        // Define Schema
        $schemaRepository = new SchemaRepository();
        $schema = $schemaRepository->getByName($lineData['schema_name']);
        $this->setSchemaId($schema->getKey());

        // Execute
        $addRequest = new Request(['data' => $lineData]);
        $addLineReceiver = new AddLineReceiver();
        $addLineReceiver->addLine($addRequest->toArray());
    }
}
