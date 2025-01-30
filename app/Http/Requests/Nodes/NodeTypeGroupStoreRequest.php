<?php

namespace App\Http\Requests\Nodes;

use App\DTO\NodeTypeGroupDto;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Node Type Group Store Request
 */
class NodeTypeGroupStoreRequest extends FormRequest
{
    /**
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string'],
            'description' => ['string'],
            'short_title' => ['string']
        ];
    }

    /**
     * @return NodeTypeGroupDto
     */
    public function dto(): NodeTypeGroupDto
    {
        return new NodeTypeGroupDto(
            $this->string('title'),
            $this->input('description'),
            $this->input('short_title')
        );
    }
}
