<?php

namespace App\Exceptions\Repositories;

use RuntimeException;

class MissingClassNameException extends RuntimeException
{
    public function __construct($repositoryClass)
    {
        parent::__construct("$repositoryClass repository does not have className property");
    }
}
