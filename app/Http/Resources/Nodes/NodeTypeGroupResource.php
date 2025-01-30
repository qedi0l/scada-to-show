<?php

namespace App\Http\Resources\Nodes;

use App\Models\MnemoSchemaNodeTypeGroup;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * MnemoSchemaNodeTypeGroup Resource
 *
 * @mixin MnemoSchemaNodeTypeGroup
 */
class NodeTypeGroupResource extends JsonResource
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
            'description' => $this->description,
            'short_title' => $this->short_title,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
            'types' => NodeTypeResourceForGroup::collection($this->whenLoaded('types')),
        ];
    }
}
