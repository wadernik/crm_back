<?php

namespace App\Http\Requests\Roles;

use Illuminate\Foundation\Http\FormRequest;

class CreateRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:64',
            'label' => 'sometimes|string|max:128|nullable',
            'permissions' => 'sometimes|array|nullable',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => __('attributes.role.name'),
            'label' => __('attributes.role.label'),
            'permissions' => __('attributes.role.permissions'),
        ];
    }
}
