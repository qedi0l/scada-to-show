<?php

namespace App\Http\Resources\Nodes;

use App\Models\MnemoSchemaNodeOptions;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * MnemoSchemaNodeOptions Resource
 *
 * @mixin MnemoSchemaNodeOptions
 */
class MnemoSchemaNodeOptionsResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'appearance' => MnemoSchemaNodeAppearanceResource::make($this->resource->node->appearance) ?? null,
            'geometry' => MnemoSchemaNodeGeometryResource::make($this->resource->node->geometry) ?? null,
            'value' => $this->value,
            'z_index' => $this->z_index,
            'parent_id' => $this->parent_id,
            'hardware_code' => $this->hardware_code,
            'parameter_code' => $this->parameter_code,
        ];
    }
}
