<?php

namespace App\Http\Requests\Comments;

use Illuminate\Foundation\Http\FormRequest;

final class CreateCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'comment' => 'required|string|max:2056',
        ];
    }
}