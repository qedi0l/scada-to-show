<?php

namespace App\Commands\SchemaOperation;

use App\Commands\AbstractCommand;
use App\Receivers\SchemaOperation\ChangeSchemaTitleReceiver;

class SchemaOperationChangeTitle extends AbstractCommand
{

    /**
     * Change schema title
     * @return void
     */
    public function execute(): void
    {
        // Validate
        $this->request->validate([
            'data.schema_title' => 'required|string',
            'data.schema_name' => 'required|string'
        ]);

        // Define Schema

        // Execute
        $receiver = new ChangeSchemaTitleReceiver();
        $receiver->changeSchemaTitle($this->request->toArray());

        // Set Changes and Response Data
        $this->setResponseData(null);
    }

    /**
     * Undo changing of schema title
     * @return void
     */
    public function undo(): void
    {
        // Validate

        // Define Schema

        // Execute
    }
}
