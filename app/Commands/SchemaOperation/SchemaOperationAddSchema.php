<?php

namespace App\Commands\SchemaOperation;

use App\Commands\AbstractCommand;
use App\Receivers\SchemaOperation\AddSchemaReceiver;
use Exception;

class SchemaOperationAddSchema extends AbstractCommand
{

    /**
     * Add schema
     * @return void
     * @throws Exception
     */
    public function execute(): void
    {
        // Validate
        $this->request->validate([
            'data.schema_title' => 'required|string',
            'data.schema_name' => 'nullable|string'
        ]);

        // Define Schema

        // Execute
        $receiver = new AddSchemaReceiver();
        $schema = $receiver->addSchema($this->request->toArray());

        // Set Changes and Response Data
        $this->setResponseData(['schema_name' => $schema->name]);
    }

    /**
     * Undo adding of schema
     * @return void
     */
    public function undo(): void
    {
        // Validate

        // Define Schema

        // Execute

    }
}
