<?php

namespace App\Http\Resources\Lines;

use App\Models\MnemoSchemaLineArrowType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin MnemoSchemaLineArrowType
 */
class LineArrowTypeResource extends JsonResource
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
            'arrow_type_title' => $this->arrow_type_title,
            'arrow_type_label' => $this->arrow_type_label,
        ];
    }
}
