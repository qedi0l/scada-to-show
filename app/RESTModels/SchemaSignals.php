<?php

namespace App\RESTModels;

class SchemaSignals
{
    public string $schemaTitle;
    public string $schemaName;
    public array $signals;
    public ?array $commands;

    public function __construct(string $schemaTitle, string $schemaName, array $signals, ?array $commands)
    {
        $this->schemaTitle = $schemaTitle;
        $this->schemaName = $schemaName;
        $this->commands = $commands;
        $this->signals = $signals;
    }
}
