<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use function __;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // 'username' => 'required|string|max:64',
            'id' => 'required|int',
            'password' => 'required|string|min:6',
        ];
    }

    public function attributes(): array
    {
        return [
            'id' => __('attributes.user.id'),
            'password' => __('attributes.user.password'),
        ];
    }
}