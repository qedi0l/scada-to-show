<?php

namespace App\Http\Resources\Lines;

use App\Models\MnemoSchemaLineType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin MnemoSchemaLineType
 */
class LineTypeResource extends JsonResource
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
            'line_type_label' => $this->line_type_label,
        ];
    }
}
