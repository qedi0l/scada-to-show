<?php

namespace App\Http\Resources\Nodes;

use App\Models\MnemoSchemaNodeType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin MnemoSchemaNodeType
 */
class NodeTypeResource extends JsonResource
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
            'type' => $this->type,
            'hardware_type' => $this->hardware_type,
            'title' => $this->title,
            'svg' => $this->svg,
            'node_type_group_id' => $this->node_type_group_id,
            'service_type' => $this->service_type,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ];
    }
}
