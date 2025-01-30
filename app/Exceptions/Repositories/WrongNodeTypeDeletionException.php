<?php

namespace App\Exceptions\Repositories;

use RuntimeException;

class WrongNodeTypeDeletionException extends RuntimeException
{
    public function __construct(string $message = "Can't delete Default or Transparent node types", int $code = 500)
    {
        parent::__construct($message, $code);
    }
}
