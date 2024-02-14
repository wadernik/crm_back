<?php

namespace App\Http\Requests\Orders;

use App\Models\Order\Contact\ContactTypeEnum;
use App\Rules\OrderContactTypeValueRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use function __;

class CreateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'manufacturer_id' => 'required|integer',
            'source_id' => 'required|integer',
            'seller_id' => 'required|integer',
            'user_id' => 'sometimes|integer',
            'inspector_id' => 'sometimes|integer',
            'phone' => 'sometimes|regex:/^\d{11}$/',
            'accepted_date' => 'required|date_format:Y-m-d',
            'order_date' => 'required|date_format:Y-m-d|after_or_equal:tomorrow',
            'order_time' => 'required|date_format:H:i',
            'number_external' => 'sometimes|string|nullable',
            //
            'items' => 'required|array|min:1',
            'items.*.title_id' => 'sometimes|integer',
            'items.*.unit_id' => 'required|integer',
            // 'items.*.name' => 'sometimes|string|max:255',
            'items.*.amount' => 'required|string',
            'items.*.label' => 'sometimes|string|max:255|nullable',
            'items.*.comment' => 'sometimes|string|max:255|nullable',
            'items.*.decoration' => 'sometimes|string|max:255|nullable',
            'items.*.decoration_type' => 'sometimes|integer|nullable',
            'items.*.files' => 'sometimes|array|nullable',
            'contacts' => 'required|array',
            'contacts.type' => ['required', Rule::enum(ContactTypeEnum::class)],
            'contacts.value' => ['required', 'string', new OrderContactTypeValueRule],
            // 'contacts.*.type' => ['required', Rule::enum(ContactTypeEnum::class)],
            // 'contacts.*.value' => ['required', 'string', new OrderContactTypeValueRule],
        ];
    }

    public function attributes(): array
    {
        return [
            'manufacturer_id' => __('attributes.order.manufacturer_id'),
            'source_id' => __('attributes.order.source_id'),
            'seller_id' => __('attributes.order.seller_id'),
            'accepted_date' => __('attributes.order.accepted_date'),
            'order_date' => __('attributes.order.order_date'),
            'order_time' => __('attributes.order.order_time'),
            'phone' => __('attributes.order.phone'),
            'items' => __('attributes.order.items'),
            // 'items.*.name' => __('attributes.order.name'),
            'items.*.unit_id' => __('attributes.order.unit_id'),
            'items.*.label' => __('attributes.order.label'),
            'items.*.comment' => __('attributes.order.comment'),
            'items.*.decoration' => __('attributes.order.decoration'),
            'items.*.decoration_type' => __('attributes.order.decoration_type'),
            'items.*.amount' => __('attributes.order.amount'),
            'items.*.files' => __('attributes.order.files'),
        ];
    }
}