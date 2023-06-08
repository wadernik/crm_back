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
            'user_id' => 'sometimes|integer|nullable',
            'role_id' => 'sometimes|integer|nullable',
            'date_start' => 'required|date_format:Y-m-d',
            'date_end' => 'sometimes|date_format:Y-m-d',
        ];
    }
}