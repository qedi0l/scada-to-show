<?php

namespace App\Receivers\NodeOperation;

use App\DTO\NodeAppearanceDto;
use App\Receivers\NodeOperation\NodeOperationInterfaces\INodeOperationChangeNodeSizeAction;
use App\Repositories\NodeAppearanceRepository;

class ChangeNodeSizeReceiver implements INodeOperationChangeNodeSizeAction
{
    protected NodeAppearanceRepository $nodeAppearanceRepository;

    public function __construct()
    {
        $this->nodeAppearanceRepository = new NodeAppearanceRepository();
    }

    /**
     * @param array $request
     * @return string
     */
    public function changeNodeSize(array $request): string
    {
        $requestData = $request['data'];

        $nodeAppearance = $this->nodeAppearanceRepository->getByNodeId($requestData['node_id']);

        $dto = new NodeAppearanceDto(
            nodeId: $nodeAppearance->node_id,
            width: array_key_exists('width', $requestData)
                ? $requestData['width']
                : $nodeAppearance->width,
            height: array_key_exists('height', $requestData)
                ? $requestData['height']
                : $nodeAppearance->height,
            svgUrl: $nodeAppearance->svg_url,
            minSvg: $nodeAppearance->min_svg,
        );
        $this->nodeAppearanceRepository->update($nodeAppearance, $dto);

        $widthSet = isset($requestData['width']);
        $heightSet = isset($requestData['height']);

        return $widthSet && $heightSet
            ? 'Width and height changed successfully'
            : ($widthSet
                ? 'Width changed successfully'
                : ($heightSet
                    ? 'Height changed successfully'
                    : 'No parameters provided, no changes made'
                )
            );
    }
}
