<?php

namespace App\Http\Requests\OAuth\Vk;

use Illuminate\Foundation\Http\FormRequest;

class VkCatchRedirectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => 'required_without:access_token|string',
            'access_token' => 'required_without:code|string',
            'expires_in' => 'sometimes|integer',
            'user_id' => 'sometimes|integer',
            'access_denied' => 'sometimes|string',
            'error_description' => 'sometimes|string',
        ];
    }
}
