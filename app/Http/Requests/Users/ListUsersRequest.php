<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class ListUsersRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'filter.id' => 'sometimes|integer',
            'filter.first_name' => 'sometimes|string|max:128',
            'filter.last_name' => 'sometimes|string|max:128|nullable',
            'filter.role_id' => 'sometimes|integer',
            'filter.is_online' => 'sometimes|string',
            'limit' => 'sometimes',
            'page' => 'sometimes',
            'sort' => 'sometimes|string',
            'order' => 'sometimes|string',
        ];
    }

    public function attributes(): array
    {
        return [
            'filter.first_name' => __('attributes.user.first_name'),
            'filter.last_name' => __('attributes.user.last_name'),
            'filter.role_id' => __('attributes.user.role'),
        ];
    }
}
