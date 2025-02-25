<?php

namespace App\Http\Requests\Orders;

use App\Rules\OrderContactTypeValueRule;
use Illuminate\Foundation\Http\FormRequest;
use function __;

class CreateOrderDraftRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'manufacturer_id' => 'sometimes|nullable|integer',
            'source_id' => 'sometimes|nullable|integer',
            'seller_id' => 'sometimes|nullable|integer',
            'user_id' => 'sometimes|nullable|integer',
            'inspector_id' => 'sometimes|nullable|integer',
            'phone' => 'sometimes|nullable|regex:/^\d{11}$/',
            'accepted_date' => 'sometimes|nullable|date_format:Y-m-d',
            'order_date' => 'sometimes|nullable|date_format:Y-m-d|after_or_equal:today',
            'order_time' => 'sometimes|nullable|date_format:H:i',
            'number_external' => 'sometimes|nullable|string',
            //
            'items' => 'sometimes|array',
            'items.*.title_id' => 'sometimes|nullable|integer',
            'items.*.unit_id' => 'sometimes|nullable|integer',
            // 'items.*.name' => 'sometimes|string|max:255',
            'items.*.amount' => 'sometimes|nullable|string',
            'items.*.label' => 'sometimes|nullable|string|max:1024',
            'items.*.comment' => 'sometimes|nullable|string|max:2056',
            'items.*.decoration' => 'sometimes|nullable|string|max:1024',
            'items.*.decoration_type_id' => 'sometimes|integer|nullable',
            'items.*.files' => 'sometimes|nullable|array',
            'contact' => 'sometimes|array|nullable',
            'contact.type_id' => 'sometimes|integer|nullable',
            'contact.value' => ['sometimes', 'string', new OrderContactTypeValueRule],
            'delivery' => 'sometimes|nullable',
            'delivery.courier_id' => 'sometimes|integer',
            'delivery.address' => 'sometimes|string|max:2056|nullable',
            'delivery.delivery_price' => 'sometimes|integer|gt:0|nullable',
            'delivery.delivery_date' => 'sometimes|date_format:Y-m-d|nullable',
            'delivery.client_phone' => 'sometimes|regex:/^\d{11}$/|nullable',
            'delivery.delivered' => 'sometimes|boolean',
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
            'contact.value' => __('attributes.order.contact.value'),
            'delivery' => __('attributes.order.delivery.name'),
            'delivery.courier_id' => __('attributes.order.delivery.courier_id'),
            'delivery.address' => __('attributes.order.delivery.address'),
            'delivery.delivery_price' => __('attributes.order.delivery.delivery_price'),
            'delivery.delivery_date' => __('attributes.order.delivery.delivery_date'),
            'delivery.client_phone' => __('attributes.order.delivery.client_phone'),
            'delivery.delivered' => __('attributes.order.delivery.delivered'),
        ];
    }
}