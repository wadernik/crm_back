<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;
use function __;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => 'sometimes|string|max:128',
            'last_name' => 'sometimes|string|max:128|nullable',
            'phone' => 'sometimes|regex:/^\d{11}$/',
            'email' => 'sometimes|string|email|nullable',
            'birth_date' => 'sometimes|date_format:Y-m-d|before:today|after:1920-01-01|nullable',
            'sex' => 'sometimes|integer|nullable',
            'role_id' => 'sometimes',
            'as_inspector' => 'sometimes|boolean',
            'password' => 'sometimes|string|min:6',
        ];
    }

    public function attributes(): array
    {
        return [
            'first_name' => __('attributes.user.first_name'),
            'last_name' => __('attributes.user.last_name'),
            'email' => __('attributes.user.email'),
            'phone' => __('attributes.user.phone'),
            'birth_date' => __('attributes.user.birth_date'),
            'sex' => __('attributes.user.sex'),
            'role_id' => __('attributes.user.role'),
            'password' => __('attributes.user.password'),
        ];
    }
}