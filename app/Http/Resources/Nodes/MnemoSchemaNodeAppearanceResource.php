<?php

namespace App\Http\Resources\Nodes;

use App\Models\MnemoSchemaNodeAppearance;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * MnemoSchemaNodeAppearance Resource
 *
 * @mixin MnemoSchemaNodeAppearance
 */
class MnemoSchemaNodeAppearanceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'width' => $this->width,
            'height' => $this->height,
            'min_svg' => $this->min_svg
        ];
    }
}
