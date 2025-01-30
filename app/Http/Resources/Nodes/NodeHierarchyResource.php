<?php

namespace App\Http\Resources\Nodes;

use App\Models\MnemoSchemaNode;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin MnemoSchemaNode
 */
class NodeHierarchyResource extends JsonResource
{
    /**
     *
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->getKey(),
            'title' => $this->title,
            'parent_id' => $this->options->parent_id,
            'min_svg' => $this->appearance->min_svg,
            'type' => $this->node_type?->type ?? 'default',
            'isHasChildren' => $this->children_options->count() > 0,
        ];
    }
}
