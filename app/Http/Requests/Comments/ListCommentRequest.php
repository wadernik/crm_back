<?php

declare(strict_types=1);

namespace App\Http\Requests\Comments;

use Illuminate\Foundation\Http\FormRequest;

final class ListCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'limit' => 'sometimes',
            'page' => 'sometimes',
            'sort' => 'sometimes|string',
            'order' => 'sometimes|string',
        ];
    }
}