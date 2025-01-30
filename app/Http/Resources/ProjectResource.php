<?php

namespace App\Http\Resources;

use App\Models\MnemoSchemaProject;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Project Resource
 *
 * @mixin MnemoSchemaProject
 */
class ProjectResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'short_title' => $this->short_title,
            'description' => $this->description,
        ];
    }
}
