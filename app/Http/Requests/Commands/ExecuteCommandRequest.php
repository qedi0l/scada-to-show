<?php

namespace App\Http\Requests\Commands;

use App\Enums\CommandType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Execute Command Request
 *
 * @property-read $method_title Command Method
 */
class ExecuteCommandRequest extends FormRequest
{
    /**
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'method_title' => ['required', 'string', Rule::in(CommandType::cases())],
        ];
    }
}
