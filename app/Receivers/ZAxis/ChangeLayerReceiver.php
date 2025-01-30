<?php

namespace App\Receivers\ZAxis;

use App\Enums\ZIndexEnum;
use App\Models\MnemoSchemaNodeOptions;
use App\Receivers\ZAxis\ZAxisInterfaces\IZAxisChangeLayerAction;
use App\Services\ScadaUINodeService;
use Exception;

class ChangeLayerReceiver implements IZAxisChangeLayerAction
{

    /**
     * @param array $request
     * @return MnemoSchemaNodeOptions
     * @throws Exception
     */
    public function changeLayer(array $request): MnemoSchemaNodeOptions
    {
        $service = new ScadaUINodeService();

        return match ($request['data']['option']) {
            ZIndexEnum::UP->value => $service->increaseZIndexByOne($request['data']['node_id']),
            ZIndexEnum::DOWN->value => $service->decreaseZIndexByOne($request['data']['node_id']),
            ZIndexEnum::TOP->value => $service->increaseZIndexToTheHighest($request['data']['node_id']),
            ZIndexEnum::BOTTOM->value => $service->decreaseZIndexToTheLowest($request['data']['node_id']),
        };
    }
}
