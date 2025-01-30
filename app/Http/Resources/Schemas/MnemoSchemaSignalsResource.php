<?php

namespace App\Http\Resources\Schemas;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MnemoSchemaSignalsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'schemas' =>  MnemoSchemaCommandsSignalsResource::collection($this->resource)
        ];
    }
}
