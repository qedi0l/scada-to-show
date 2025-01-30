<?php

namespace App\Http\Resources\Schemas;

use App\Http\Resources\Nodes\NodeDataResource;
use App\Models\MnemoSchema;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin MnemoSchema
 */
class MnemoSchemaNodeDataResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->getKey(),
            'title' => $this->title,
            'nodes' => NodeDataResource::collection($this->whenLoaded('nodes')),
        ];
    }
}
