<?php

namespace App\Http\Resources\Nodes;

use App\Models\MnemoSchemaNode;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin MnemoSchemaNode
 */
class NodeDataResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'options' => [NodeDataOptionResource::make($this->whenLoaded('options'))],
        ];
    }
}
