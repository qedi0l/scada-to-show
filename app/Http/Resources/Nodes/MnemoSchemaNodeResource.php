<?php

namespace App\Http\Resources\Nodes;

use App\Models\MnemoSchemaNode;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * MnemoSchemaNode Resource
 *
 * @mixin MnemoSchemaNode
 */
class MnemoSchemaNodeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     * @throws Exception
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'type_title' => $this->node_type?->title ?? 'default',
            'type_id' => $this->type_id,
            'type' => $this->node_type?->type ?? 'default',
//            'node_type_group_id' => $this->getNodeTypeGroupId($this->node_type),
            'service_type' => $this->node_type->service_type ?? null,
            'group' => $this->group_id,
            'options' => [
                "z_index" => $this->relationLoaded('options') ? $this->options->z_index : null,
                "parent_id" => $this->relationLoaded('options') ? $this->options->parent_id : null,
                "hardware_code" => $this->relationLoaded('options') ? $this->options->hardware_code : null,
                "parameter_code" => $this->relationLoaded('options') ? $this->options->parameter_code : null,

                'appearance' => MnemoSchemaNodeAppearanceResource::make($this->whenLoaded('appearance')),
                'geometry' => MnemoSchemaNodeGeometryResource::make($this->whenLoaded('geometry')),
                'link' => MnemoSchemaNodeLinkResource::make($this->whenLoaded('link')),
            ],
        ];
    }
}
