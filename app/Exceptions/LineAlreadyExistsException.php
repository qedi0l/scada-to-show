<?php

namespace App\Exceptions;

use RuntimeException;

class LineAlreadyExistsException extends RuntimeException
{
    public function __construct(int $lineId, int $code = 0)
    {
        $message = "Line with ID $lineId already exists.";
        parent::__construct($message, $code);
    }
}
