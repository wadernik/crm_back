<?php

namespace App\Http\Requests\OrderSetting;

use App\Models\OrderSetting\OrderSettingTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOrderSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => ['sometimes', Rule::enum(OrderSettingTypeEnum::class)],
            'value' => 'sometimes|string|min:1',
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