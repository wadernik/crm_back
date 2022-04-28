<?php

namespace App\Http\Requests\Users;

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
            'first_name' => 'required|string|max:128',
            'last_name' => 'sometimes|string|max:128|nullable',
            'phone' => 'required|regex:/^\d{11}$/',
            'email' => 'sometimes|string|email|nullable',
            'birth_date' => 'sometimes|date_format:Y-m-d|before:today|after:1920-01-01|nullable',
            'sex' => 'sometimes|integer|nullable',
            'role_id' => 'required',
        ];
    }

    public function attributes(): array
    {
        return [
            'username' => __('attributes.user.username'),
            'password' => __('attributes.user.password'),
            'first_name' => __('attributes.user.first_name'),
            'last_name' => __('attributes.user.last_name'),
            'email' => __('attributes.user.email'),
            'birth_date' => __('attributes.user.birth_date'),
            'role_id' => __('attributes.user.role'),
        ];
    }
}
