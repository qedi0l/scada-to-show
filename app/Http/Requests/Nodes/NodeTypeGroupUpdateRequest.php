<?php

namespace App\Http\Requests\Nodes;

use App\DTO\NodeTypeGroupDto;
use App\Models\MnemoSchemaNodeTypeGroup;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Node Type Group Update Request
 *
 * @property int $node_type_group_id
 */
class NodeTypeGroupUpdateRequest extends FormRequest
{
    /**
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'node_type_group_id' => ['required', 'integer', Rule::exists(MnemoSchemaNodeTypeGroup::class, 'id')],
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
