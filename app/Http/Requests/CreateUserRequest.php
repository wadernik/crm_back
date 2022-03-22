<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username' => 'required|string|unique:users|max:64',
            'password' => 'required|string|min:6',
            'name' => 'required|string|max:128',
        ];
    }

    public function attributes(): array
    {
        return [
            'username' => __('attributes.user.username'),
            'password' => __('attributes.user.password'),
            'name' => __('attributes.user.name'),
        ];
    }
}
