<?php

namespace App\Http\Requests\Nodes;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Node Type Group Destroy Request
 *
 * @property string $group_title
 */
class NodeTypeGroupDestroyRequest extends FormRequest
{
    /**
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'group_title' => ['required', 'string'],
        ];
    }
}
