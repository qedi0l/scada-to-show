<?php

namespace App\Exceptions;

use RuntimeException;

class NodeTypeNotFoundException extends RuntimeException
{
    public function __construct(int $typeId)
    {
        $message = "No group id found for type $typeId";
        parent::__construct($message);
    }
}
