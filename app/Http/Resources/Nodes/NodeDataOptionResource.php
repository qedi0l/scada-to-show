<?php

namespace App\Http\Resources\Nodes;

use App\Models\MnemoSchemaNodeOptions;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin MnemoSchemaNodeOptions
 */
class NodeDataOptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'parameter_code' => $this->parameter_code,
            'hardware_code' => $this->hardware_code,
        ];
    }
}
