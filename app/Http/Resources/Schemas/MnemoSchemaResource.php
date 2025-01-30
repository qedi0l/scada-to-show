<?php

namespace App\Http\Resources\Schemas;

use App\Http\Resources\Lines\MnemoSchemaLineResource;
use App\Http\Resources\Nodes\MnemoSchemaNodeResource;
use App\Models\MnemoSchema;
use App\Models\MnemoSchemaNode;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

/**
 * MnemoSchema Resource
 *
 * @mixin MnemoSchema
 */
class MnemoSchemaResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'name' => $this->name,
            'nodes' => MnemoSchemaNodeResource::collection($this->whenLoaded('nodes')),
            'service_nodes' => MnemoSchemaNodeResource::collection($this->whenLoaded('service_nodes')),
            'lines' => MnemoSchemaLineResource::collection($this->whenLoaded('lines')),
            'parent_children' => $this->getParentChild(),
        ];
    }

    /**
     * Get Parent Child Property
     *
     * @return Collection
     */
    private function getParentChild(): Collection
    {
        return $this->parent_nodes
            ->mapWithKeys(function (MnemoSchemaNode $node) {
                return [(string)$node->id => $node->children_options->pluck('node_id')->toArray()];
            });
    }
}
