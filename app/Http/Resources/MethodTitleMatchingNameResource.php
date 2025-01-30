<?php

namespace App\Http\Resources;

use App\Models\MethodTitleMatching;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin MethodTitleMatching
 */
class MethodTitleMatchingNameResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return string
     */
    public function toArray(Request $request): string
    {
        return $this->frontend_method_title;
    }
}
