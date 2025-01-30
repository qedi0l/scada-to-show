<?php

namespace App\Commands;

interface CommandInterface
{
    public function execute(): void;

    public function undo(): void;

    public function setSchemaId(int $schemaId): static;

    public function getSchemaId(): int;

    public function setChanges(array $changes): static;

    public function getChanges(): ?array;

    public function setResponseData(mixed $responseData): static;

    public function getResponseData(): mixed;
}
