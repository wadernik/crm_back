<?php

declare(strict_types=1);

namespace App\DTOs\Order;

use Illuminate\Support\Carbon;

final class CreateOrderDTO implements CreateOrderDTOInterface
{
    /**
     * @param array{
     *     manufacturer_id: int,
     *     source_id: int,
     *     seller_id: int,
     *     user_id: int,
     *     draft_id: int|null,
     *     accepted_date: string,
     *     order_date: string,
     *     order_time: string,
     *     number_external: string,
     *     inspector_id: ?int,
     *     phone: string|null,
     *     items: array<int, array{
     *          title_id: int|null,
     *          unit_id: int|null,
     *          name: string,
     *          amount: string,
     *          label: string|null,
     *          comment: string|null,
     *          decoration: string|null,
     *          decoration_type_id: int|null,
     *          files: array|null,
     *     }>,
     *     contact: array{
     *          type_id: int,
     *          value: string,
     *     },
     *     delivery: array{
     *          courier_id: int|null,
     *          address: string|null,
     *          delivery_price: int|null,
     *          delivery_date: string|null,
     *          delivered: bool,
     *     }
     * } $attributes
     */
    public function __construct(private readonly array $attributes)
    {
    }

    public function main(): array
    {
        $attributes = $this->attributes;

        unset($attributes['items']);

        return $attributes;
    }

    public function items(): array
    {
        return $this->attributes['items'];
    }

    public function contact(): array
    {
        return $this->attributes['contact'] ?? [];
    }

    public function delivery(): array
    {
        $attributes = $this->attributes['delivery'] ?? [];

        if (empty($attributes)) {
            return [];
        }

        $result = [
            'courier_id' => $attributes['courier_id'] ?? null,
            'address' => $attributes['address'] ?? null,
            'delivery_price' => $attributes['delivery_price'] ?? null,
            'delivery_date' => $attributes['delivery_date'] ?? null,
        ];

        $delivered = (bool) ($attributes['delivered'] ?? null);

        $result['delivered_at'] = $delivered ? Carbon::now() : null;

        return $result;
    }

    public function toArray(): array
    {
        return $this->attributes;
    }
}