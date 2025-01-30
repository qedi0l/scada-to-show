<?php

namespace App\Http\Resources\Schemas;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MnemoSchemaSignalsSingleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'schemas' =>  MnemoSchemaCommandsSignalsResource::make($this->resource->first())
        ];
    }
}
