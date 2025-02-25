<?php

declare(strict_types=1);

namespace App\DTOs\Order;

use Illuminate\Support\Carbon;

final class OrderDTO implements OrderDTOInterface
{
    /**
     * @param array{
     *     manufacturer_id: int|null,
     *     source_id: int|null,
     *     seller_id: int|null,
     *     user_id: int|null,
     *     draft_id: int|null,
     *     accepted_date: string|null,
     *     order_date: string|null,
     *     order_time: string|null,
     *     number_external: string|null,
     *     inspector_id: ?int|null,
     *     phone: string|null,
     *     id: int|null,
     *     items: array<int, array{
     *          id: int|null,
     *          title_id: int|null,
     *          unit_id: int|null,
     *          name: string|null,
     *          amount: string|null,
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
     *         courier_id: int|null,
     *         address: string|null,
     *         delivery_price: int|null,
     *         delivery_date: string|null,
     *         client_phone: string|null,
     *         delivered: bool,
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
        return $this->attributes['items'] ?? [];
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
            'client_phone' => $attributes['client_phone'] ?? null,
        ];

        $delivered = (bool) ($attributes['delivered'] ?? null);

        $result['delivered_at'] = $delivered ? Carbon::now() : null;

        return $result;
    }

    public function id(): ?int
    {
        return $this->attributes['id'] ?? null;
    }

    public function toArray(): array
    {
        $attributes = $this->attributes;

        unset($attributes['id']);

        return $attributes;
    }
}