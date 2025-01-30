<?php

namespace App\Commands\SchemaOperation;

use App\Commands\AbstractCommand;
use App\Receivers\SchemaOperation\DeleteSchemaReceiver;
use Laravel\Octane\Exceptions\DdException;
use Throwable;

class SchemaOperationDeleteSchema extends AbstractCommand
{
    /**
     * Delete schema
     * @throws Throwable
     * @throws DdException
     */
    public function execute(): void
    {
        // Validate
        $this->request->validate([
            'data.schema_name' => 'required|string'
        ]);

        // Define Schema

        // Execute
        $receiver = new DeleteSchemaReceiver();
        $receiver->deleteSchema($this->request->toArray());

        // Set Changes and Response Data
        $this->setResponseData(null);
    }


    /**
     * Undo deletion of schema
     * @return void
     */
    public function undo(): void
    {
        // Validate

        // Define Schema

        // Execute
    }
}
