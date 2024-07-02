<?php

namespace App\Http\Requests\OrderSetting;

use Illuminate\Foundation\Http\FormRequest;

class CreateSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type_id' => 'required|int|min:1',
            'value' => 'required|string|min:1',
        ];
    }

    public function attributes(): array
    {
        return [
            'type' => __('attributes.order_setting.type'),
            'value' => __('attributes.order_setting.value'),
        ];
    }
}