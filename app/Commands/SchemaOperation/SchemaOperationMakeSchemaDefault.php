<?php

namespace App\Commands\SchemaOperation;

use App\Commands\AbstractCommand;
use App\Receivers\SchemaOperation\MakeSchemaDefaultActionReceiver;
use Throwable;

class SchemaOperationMakeSchemaDefault extends AbstractCommand
{
    /**
     * Make schema default
     * @throws Throwable
     */
    public function execute(): void
    {
        // Validate
        $this->request->validate([
            'data.schema_name' => 'required|string'
        ]);

        // Define Schema

        // Execute
        $receiver = new MakeSchemaDefaultActionReceiver();
        $receiver->makeSchemaDefault($this->request->toArray());

        // Set Changes and Response Data
        $this->setResponseData(null);
    }

    /**
     * Undo making schema default
     * @return void
     */
    public function undo(): void
    {
        // Validate

        // Define Schema

        // Execute
    }
}
