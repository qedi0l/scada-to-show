<?php

namespace App\Receivers\LineOperation;

use App\Receivers\LineOperation\LineOperationInterfaces\ILineOperationDeleteLineAction;
use App\Repositories\LineRepository;
use Throwable;

class DeleteLineReceiver implements ILineOperationDeleteLineAction
{
    protected LineRepository $lineRepository;

    public function __construct()
    {
        $this->lineRepository = new LineRepository();
    }


    /**
     * @param array $request
     * @return bool|null
     * @throws \Exception
     */
    public function deleteLine(array $request): ?bool
    {
        return $this->lineRepository->destroy($request['data']['line_id']);
    }
}
