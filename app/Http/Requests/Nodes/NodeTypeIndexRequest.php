<?php

namespace App\Http\Requests\Nodes;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property bool $isServiceType
 */
class NodeTypeIndexRequest extends FormRequest
{
    /**
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'isServiceType' => ['nullable', 'boolean'],
        ];
    }
}
