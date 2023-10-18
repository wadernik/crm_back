<?php

declare(strict_types=1);

namespace App\Http\Requests\Board\Board;

use Illuminate\Foundation\Http\FormRequest;
use function __;

final class CreateBoardRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'file_id' => 'sometimes|integer|nullable',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => __('attributes.boards.board.name'),
            'file_id' => __('attributes.boards.board.file_id'),
        ];
    }
}