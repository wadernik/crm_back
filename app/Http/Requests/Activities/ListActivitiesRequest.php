<?php

namespace App\Http\Requests\Activities;

use Illuminate\Foundation\Http\FormRequest;

class ListActivitiesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'filter.id' => 'sometimes',
            'filter.subject_id' => 'sometimes',
            'filter.subject' => 'sometimes|string',
            'filter.causer_id' => 'sometimes|string',
            'filter.date_start' => 'sometimes|date_format:Y-m-d',
            'filter.date_end' => 'sometimes|date_format:Y-m-d',
            'limit' => 'sometimes',
            'page' => 'sometimes',
            'sort' => 'sometimes|string',
            'order' => 'sometimes|string',
        ];
    }
}