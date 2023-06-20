<?php

namespace App\Http\Requests\Reports;

use Illuminate\Foundation\Http\FormRequest;

class UserReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'filter.user_id' => 'sometimes|integer|nullable',
            'filter.role_id' => 'sometimes|integer|nullable',
            'filter.date_start' => 'required|date_format:Y-m-d',
            'filter.date_end' => 'sometimes|date_format:Y-m-d',
        ];
    }
}