<?php

namespace App\Http\Resources\Schemas;

use App\Models\MnemoSchema;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * @mixin MnemoSchema
 */
class MnemoSchemaTitlesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->title,
            'name' => $this->name,
            "preview" => $this->preview_file_name
                ? Storage::disk('schema_previews')->url($this->preview_file_name)
                : null,
        ];
    }
}
