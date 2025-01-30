<?php

namespace App\Receivers\NodeOperation\NodeOperationInterfaces;

use App\Models\MnemoSchemaNodeLink;

interface INodeOperationChangeNodeLinkAction extends INodeOperationAction
{
    /**
     * Change node link
     * @param array $request
     * @return MnemoSchemaNodeLink
     */
    public function changeNodeLink(array $request): MnemoSchemaNodeLink;
}
