<?php

namespace App\Receivers\LineOperation\LineOperationInterfaces;

interface ILineOperationDeleteLineAction extends ILineOperationAction
{
    /**
     * Deletes line
     * @param array $request
     * @return bool|null
     */
    public function deleteLine(array $request): ?bool;
}
