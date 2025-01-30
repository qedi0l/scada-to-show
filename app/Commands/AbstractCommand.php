<?php

namespace App\Commands;

use Illuminate\Http\Request;

abstract class AbstractCommand implements CommandInterface
{
    protected ?array $changes = null;

    protected ?int $schemaId = null;

    protected mixed $responseData = null;

    public function __construct(protected Request $request)
    {
    }

    public function execute(): void
    {
        // Validate

        // Define Schema

        // Execute

        // Set Changes and Response Data
    }

    public function undo(): void
    {
        // Validate

        // Define Schema

        // Execute
    }

    public function setSchemaId(int $schemaId): static
    {
        $this->schemaId = $schemaId;
        return $this;
    }

    public function getSchemaId(): int
    {
        return $this->schemaId;
    }

    public function setChanges(array $changes): static
    {
        $this->changes = $changes;
        return $this;
    }

    public function getChanges(): ?array
    {
        return $this->changes;
    }

    public function setResponseData(mixed $responseData): static
    {
        $this->responseData = $responseData;
        return $this;
    }

    public function getResponseData(): mixed
    {
        return $this->responseData;
    }
}
