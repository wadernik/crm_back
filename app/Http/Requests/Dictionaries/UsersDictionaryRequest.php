<?php

namespace App\Http\Requests\Dictionaries;

class UsersDictionaryRequest extends AbstractBaseDictionaryRequest
{
    public function rules(): array
    {
        return array_merge(
            parent::rules(),
            [
                'filter.role_id' => 'sometimes|integer',
                'filter.role_ids' => 'sometimes|array',
            ]
        );
    }
}
