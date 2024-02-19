<?php

namespace App\Http\Requests\Orders;

use App\Rules\OrderContactTypeValueRule;
use Illuminate\Foundation\Http\FormRequest;
use function __;

class UpdateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'manufacturer_id' => 'sometimes|integer',
            'source_id' => 'sometimes|integer',
            'seller_id' => 'sometimes|integer',
            'user_id' => 'sometimes|integer',
            'inspector_id' => 'sometimes|integer',
            'phone' => 'sometimes|regex:/^\d{11}$/|nullable',
            'accepted_date' => 'sometimes|date_format:Y-m-d',
            'order_date' => 'sometimes|date_format:Y-m-d|after_or_equal:tomorrow',
            'order_time' => 'sometimes|date_format:H:i',
            'number_external' => 'sometimes|string|nullable',
            //
            'items' => 'sometimes|array',
            'items.*.id' => 'sometimes|integer|min:1',
            'items.*.title_id' => 'sometimes|integer',
            'items.*.unit_id' => 'required|integer',
            // 'items.*.name' => 'sometimes|string|max:255',
            'items.*.amount' => 'sometimes|string',
            'items.*.label' => 'sometimes|string|max:255|nullable',
            'items.*.comment' => 'sometimes|string|max:255|nullable',
            'items.*.decoration' => 'sometimes|string|max:255|nullable',
            'items.*.decoration_type_id' => 'sometimes|integer|nullable',
            'items.*.files' => 'sometimes|array|nullable',
            'contact' => 'sometimes|array',
            'contact.id' => 'sometimes|integer|min:1',
            'contact.type_id' => 'required|integer',
            'contact.value' => ['sometimes', 'string', new OrderContactTypeValueRule],
            // 'contact.type' => ['sometimes', Rule::enum(ContactTypeEnum::class)],
            // 'contacts.*.id' => 'sometimes|integer|min:1',
            // 'contacts.*.type' => ['sometimes', Rule::enum(ContactTypeEnum::class)],
            // 'contacts.*.value' => ['sometimes', 'string', new OrderContactTypeValueRule],
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
            'items.*.decoration_type_id' => __('attributes.order.decoration_type'),
            'items.*.amount' => __('attributes.order.amount'),
            'items.*.files' => __('attributes.order.files'),
        ];
    }
}