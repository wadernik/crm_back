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
     *     files: array|null,
     *     inspector_id: ?int,
     *     phone: string,
     *     items: array<int, array{
     *          name: string,
     *          amount: string,
     *          label: string|null,
     *          comment: string|null,
     *          decoration: string|null
     *     }>,
     * } $attributes
     */
    public function __construct(private array $attributes)
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

    public function files(): array
    {
        return $this->attributes['files'] ?? [];
    }

    public function toArray(): array
    {
        $attributes = $this->attributes;

        unset($attributes['files']);

        return $attributes;
    }
}