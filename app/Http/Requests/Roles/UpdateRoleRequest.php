<?php

namespace App\Http\Requests\Roles;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:64',
            'label' => 'sometimes|string|max:128|nullable',
            'permissions' => 'sometimes|array|nullable',
        ];
    }
}