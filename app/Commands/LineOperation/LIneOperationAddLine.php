<?php

namespace App\Commands\LineOperation;

use App\Commands\AbstractCommand;
use App\Receivers\LineOperation\AddLineReceiver;
use App\Receivers\LineOperation\DeleteLineReceiver;
use App\Repositories\LineRepository;
use App\Repositories\SchemaRepository;
use Illuminate\Http\Request;
use Throwable;
use Exception;

class LIneOperationAddLine extends AbstractCommand
{
    /**
     * Create line
     * @return void
     * @throws Exception
     * @throws Throwable
     */
    public function execute(): void
    {
        // Validate
        $this->request->validate([
            'data' => ['required', 'array'],
            'data.schema_name' => ['required', 'string'],
            'data.options' => ['required', 'array'],
            'data.options.label' => ['nullable', 'string'],
            'data.options.appearance' => ['array'],
            'data.options.appearance.color' => ['nullable', 'string'],
            'data.options.appearance.opacity' => ['nullable', 'integer'],
            'data.options.appearance.width' => ['nullable', 'integer'],
            'data.first_node' => ['required', 'integer'],
            'data.second_node' => ['required', 'integer'],
            'data.source_position' => ['required', 'integer'],
            'data.target_position' => ['required', 'integer'],
        ]);

        // Define Schema
        $schema = (new SchemaRepository())->getByName($this->request->input('data.schema_name'));
        $this->setSchemaId($schema->getKey());

        // Execute
        $receiver = new AddLineReceiver();
        $line = $receiver->addLine($this->request->toArray());

        // Set Changes and Response Data
        $this
            ->setChanges([
                'line_id' => $line->getKey(),
                'request' => $this->request->all()
            ])
            ->setResponseData([
                'line_id' => $line->getKey()
            ]);
    }

    /**
     * Undo line adding
     * @return void
     * @throws Throwable
     */
    public function undo(): void
    {
        // Validate
        $this->request->validate([
            'line_id' => ['required', 'integer'],
        ]);

        // Define Schema
        $line = (new LineRepository())->getById($this->request->get('line_id'));
        $this->setSchemaId($line->schema_id);

        // Execute
        $deleteRequest = new Request(['data' => ['line_id' => $line->getKey()]]);
        $receiver = new DeleteLineReceiver();
        $receiver->deleteLine($deleteRequest->toArray());
    }
}
