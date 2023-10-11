<?php

declare(strict_types=1);

namespace App\Http\Requests\Dictionaries;

use function array_merge;

final class OrderTitlesDictionaryRequest extends AbstractDictionaryRequest
{
    public function rules(): array
    {
        return array_merge(
            parent::rules(),
            [
                'filter.value' => 'sometimes|string',
            ]
        );
    }
}