<?php

namespace App\Http\Resources\Schemas;

use App\Models\MnemoSchema;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * @mixin MnemoSchema
 */
class MnemoSchemaSimpleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->getKey(),
            "name" => $this->name,
            "title" => $this->title,
            "is_active" => $this->is_active,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "project_id" => $this->project_id,
            "default" => $this->default,
            "preview" => $this->preview_file_name
                ? Storage::disk('schema_previews')->url($this->preview_file_name)
                : null,
        ];
    }
}
