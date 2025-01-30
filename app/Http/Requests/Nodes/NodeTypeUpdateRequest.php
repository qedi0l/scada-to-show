<?php

namespace App\Http\Requests\Nodes;

use App\DTO\NodeTypeDto;
use App\Models\MnemoSchemaNodeType;
use App\Models\MnemoSchemaNodeTypeGroup;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Node Type Update Request
 *
 * @property MnemoSchemaNodeType|null $nodeType
 */
class NodeTypeUpdateRequest extends FormRequest
{
    public MnemoSchemaNodeType|null $nodeType = null;

    /**
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        $this->nodeType = MnemoSchemaNodeType::query()->find($this->integer('node_type_id'));

        return [
            'node_type_id' => ['required', 'integer', Rule::exists(MnemoSchemaNodeType::class, 'id')],
            'type' => ['string'],
            'hardware_type' => ['string'],
            'title' => ['string'],
            'svg' => ['string'],
            'node_type_group_title' => ['string', Rule::exists(MnemoSchemaNodeTypeGroup::class, 'title')],
        ];
    }

    public function dto(): NodeTypeDto
    {
        $nodeTypeGroup = MnemoSchemaNodeTypeGroup::query()
            ->where('title', $this->string('node_type_group_title'))
            ->first();

        return new NodeTypeDto(
            $this->string('type'),
            $this->string('hardware_type'),
            $this->string('svg'),
            $nodeTypeGroup->getKey(),
            $this->string('title'),
            $this->nodeType->service_type
        );
    }
}
