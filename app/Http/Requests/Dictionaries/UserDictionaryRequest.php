<?php

namespace App\Http\Requests\Dictionaries;

use function array_merge;

class UserDictionaryRequest extends AbstractDictionaryRequest
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