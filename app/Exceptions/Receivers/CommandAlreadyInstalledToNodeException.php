<?php

namespace App\Exceptions\Receivers;

class CommandAlreadyInstalledToNodeException extends \Exception
{
    protected $message = 'Command already installed.';
}
