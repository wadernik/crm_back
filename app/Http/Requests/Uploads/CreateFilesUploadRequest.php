<?php

namespace App\Http\Requests\Uploads;

use Illuminate\Foundation\Http\FormRequest;

class CreateFilesUploadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'files' => 'required|array',
            'files.*.file' => 'required|mimes:jpeg,jpg,png|max:4096',
        ];
    }
}
