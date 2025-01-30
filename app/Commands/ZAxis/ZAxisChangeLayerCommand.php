<?php

namespace App\Commands\ZAxis;

use App\Commands\AbstractCommand;
use App\Enums\ZIndexEnum;
use App\Receivers\ZAxis\ChangeLayerReceiver;
use Exception;
use Illuminate\Validation\Rule;

class ZAxisChangeLayerCommand extends AbstractCommand
{

    /**
     * Change layer
     * @return void
     * @throws Exception
     */
    public function execute(): void
    {
        // Validate
        $this->request->validate([
            'data.node_id' => 'required|integer',
            'data.option' => ['required', 'string', Rule::in(ZIndexEnum::cases())]
        ]);

        // Define Schema

        // Execute
        $receiver = new ChangeLayerReceiver();
        $nodeOptions = $receiver->changeLayer($this->request->toArray());

        // Set Changes and Response Data
        $this->setResponseData([
            'status' => 200,
            'z_index' => $nodeOptions->z_index
        ]);
    }

    /**
     * Undo changing on layer
     * @return void
     */
    public function undo(): void
    {
        // Validate

        // Define Schema

        // Execute
    }
}
