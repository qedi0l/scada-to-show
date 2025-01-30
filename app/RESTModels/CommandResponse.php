<?php

namespace App\RESTModels;

class CommandResponse
{
    public int $parameterCode;
    public int $hardwareCode;
    public string $title;
    public string|null $description;
    public string $shortTitle;
    public string $signalType;
    public MeasurementUnit $measurementUnit;
}
