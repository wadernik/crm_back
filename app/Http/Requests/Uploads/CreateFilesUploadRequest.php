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
            'files.*' => 'required|mimes:jpeg,jpg,png|max:10240',
        ];
    }

    public function attributes(): array
    {
        return [
            'files.*' => __('attributes.files.file'),
        ];
    }
}
