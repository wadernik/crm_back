<?php

declare(strict_types=1);

namespace App\Http\Requests\Board\Group;

use Illuminate\Foundation\Http\FormRequest;
use function __;

final class UpdateGroupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'board_id' => 'sometimes|integer|nullable',
            'name' => 'sometimes|string|max:255',
            'sort' => 'sometimes|integer|nullable',
        ];
    }

    public function attributes(): array
    {
        return [
            'board_id' => __('attributes.boards.group.board_id'),
            'name' => __('attributes.boards.group.name'),
            'sort' => __('attributes.boards.group.sort'),
        ];
    }
}