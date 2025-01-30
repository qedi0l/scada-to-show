<?php

namespace App\Receivers\NodeOperation;

use App\DTO\NodeOptionsDto;
use App\Receivers\NodeOperation\NodeOperationInterfaces\INodeOperationChangeNodeParameterAndHardwareCodesAction;
use App\Repositories\NodeOptionsRepository;

class ChangeNodeParameterAndHardwareCodesReceiver implements INodeOperationChangeNodeParameterAndHardwareCodesAction
{
    protected NodeOptionsRepository $nodeOptionsRepository;

    public function __construct()
    {
        $this->nodeOptionsRepository = new NodeOptionsRepository();
    }

    /**
     * @param array $request
     * @return string
     */
    public function changeNodeParameterAndHardwareCodes(array $request): string
    {
        $requestData = $request['data'];
        $nodeId = $requestData['node_id'];

        $nodeOptions = $this->nodeOptionsRepository->getByNodeId($nodeId);

        $dto = new NodeOptionsDto(
            nodeId: $nodeOptions->node_id,
            zIndex: $nodeOptions->z_index,
            parameterCode: array_key_exists('parameter_code', $requestData)
                ? $requestData['parameter_code']
                : $nodeOptions->parameter_code,
            hardwareCode: array_key_exists('hardware_code', $requestData)
                ? $requestData['hardware_code']
                : $nodeOptions->hardware_code,
            parentId: $nodeOptions->parent_id,
            label: $nodeOptions->label,
        );
        $this->nodeOptionsRepository->update($nodeOptions, $dto);

        $parameterCodeSet = array_key_exists('parameter_code', $requestData);
        $hardwareCodeSet = array_key_exists('hardware_code', $requestData);

        return ($parameterCodeSet && $hardwareCodeSet)
            ? 'Hardware and parameter codes changed successfully'
            : ($parameterCodeSet
                ? 'Parameter code changed successfully'
                : ($hardwareCodeSet
                    ? 'Hardware code changed successfully'
                    : 'No parameters provided, no changes made'
                )
            );
    }
}
