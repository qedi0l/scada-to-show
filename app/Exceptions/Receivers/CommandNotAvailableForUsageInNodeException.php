<?php

namespace App\Exceptions\Receivers;

class CommandNotAvailableForUsageInNodeException extends \Exception
{
    protected $message = 'Command not available for usage in node.';
}
