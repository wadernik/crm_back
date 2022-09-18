<?php

namespace App\Http\Requests\OAuth\Vk;

use Illuminate\Foundation\Http\FormRequest;

class VkGetAccessTokenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'access_token' => 'required|string',
            'expires_in' => 'required|integer',
            'user_id' => 'sometimes|integer',
            'access_denied' => 'sometimes|string',
            'error_description' => 'sometimes|string',
        ];
    }
}
