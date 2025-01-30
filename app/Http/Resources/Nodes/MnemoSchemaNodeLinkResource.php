<?php

namespace App\Http\Resources\Nodes;

use App\Models\MnemoSchemaNodeLink;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * MnemoSchemaNodeLink Resource
 *
 * @mixin MnemoSchemaNodeLink
 */
class MnemoSchemaNodeLinkResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'url' => $this->url
        ];
    }
}
