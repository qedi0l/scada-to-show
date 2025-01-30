<?php

namespace App\Http\Resources\Nodes;

use App\Models\MnemoSchemaNodeGeometry;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * MnemoSchemaNodeGeometry Resource
 *
 * @mixin MnemoSchemaNodeGeometry
 */
class MnemoSchemaNodeGeometryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'x' => $this->x,
            'y' => $this->y,
            'rotate' => $this->rotation,
        ];
    }
}
