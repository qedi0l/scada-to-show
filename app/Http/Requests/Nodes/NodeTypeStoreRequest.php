<?php

namespace App\Http\Requests\Nodes;

use App\DTO\NodeTypeDto;
use App\Models\MnemoSchemaNodeTypeGroup;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Node Type Store Request
 */
class NodeTypeStoreRequest extends FormRequest
{
    /**
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'type' => ['required', 'string'],
            'hardware_type' => ['required', 'string'],
            'title' => ['string'],
            'svg' => ['required', 'string'],
            'node_type_group_title' => ['required', 'string', Rule::exists(MnemoSchemaNodeTypeGroup::class, 'title')],
        ];
    }

    public function dto(): NodeTypeDto
    {
        $nodeTypeGroupId = MnemoSchemaNodeTypeGroup::query()
            ->where('title', $this->input('node_type_group_title'))
            ->first()
            ->getKey();

        return new NodeTypeDto(
            $this->string('type'),
            $this->string('hardware_type'),
            $this->string('svg'),
            $nodeTypeGroupId,
            $this->string('title'),
        );
    }
}
