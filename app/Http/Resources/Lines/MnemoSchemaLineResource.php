<?php

namespace App\Http\Resources\Lines;

use App\Models\MnemoSchemaLine;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * MnemoSchemaLine Resource
 *
 * @mixin MnemoSchemaLine
 */
class MnemoSchemaLineResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'first_node' => $this->first_node,
            'second_node' => $this->second_node,
            'first_position'=>$this->source_position,
            'second_position'=>$this->target_position,
            'options' => [
                'label' => $this->options?->text,
                'first_arrow' => $this->options?->first_arrow_type?->arrow_type_title,
                'second_arrow' => $this->options?->second_arrow_type?->arrow_type_title,
                'type' => $this->options?->type?->type,
                'appearance' => [
                    'color' => $this->appearance?->color,
                    'opacity' => $this->appearance?->opacity,
                    'width' => $this->appearance?->width
                ]
            ]
        ];
    }
}
