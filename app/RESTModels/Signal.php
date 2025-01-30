<?php

namespace App\RESTModels;

class Signal
{
    public string $title;
    public int $parameterCode;
    public int $hardwareCode;
    public ?string $description;

    public function __construct(string $title, int $transportSignalId, int $transportHardwareId, ?string $description)
    {
        $this->title = $title;
        $this->parameterCode = $transportSignalId;
        $this->hardwareCode = $transportHardwareId;
        $this->description = $description;
    }
}
