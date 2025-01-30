<?php

namespace App\Http\Resources\Nodes;

use App\Models\MnemoSchemaNodeGeometry;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin MnemoSchemaNodeGeometry
 */
class NodeGeometryResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'node_id' => $this->node_id,
            'x' => $this->x,
            'y' => $this->y,
            'rotation' => $this->rotation,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
