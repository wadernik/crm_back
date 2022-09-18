<?php

namespace App\Http\Requests\OAuth\Vk;

use Illuminate\Foundation\Http\FormRequest;

class VkGetCodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => 'required|string',
            'access_denied' => 'sometimes|string',
            'error_description' => 'sometimes|string',
        ];
    }
}
