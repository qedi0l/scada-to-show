<?php

namespace App\Http\Resources\Nodes;

use App\Models\MnemoSchemaNodeType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin MnemoSchemaNodeType
 */
class NodeTypeResourceForGroup extends JsonResource
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
            'type' => $this->type,
            'hardware_type' => $this->hardware_type
        ];
    }
}
