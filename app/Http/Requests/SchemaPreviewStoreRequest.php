<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

/**
 * Schema Set Preview Request
 */
class SchemaPreviewStoreRequest extends FormRequest
{
    /**
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'preview' => ['required', 'file', File::types(['svg', 'png', 'jpg', 'jpeg'])],
        ];
    }
}
