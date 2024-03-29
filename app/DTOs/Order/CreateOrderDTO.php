<?php

declare(strict_types=1);

namespace App\DTOs\Order;

final class CreateOrderDTO implements CreateOrderDTOInterface
{
    /**
     * @param array{
     *     manufacturer_id: int,
     *     source_id: int,
     *     seller_id: int,
     *     user_id: int,
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

    public function toArray(): array
    {
        return $this->attributes;
    }
}