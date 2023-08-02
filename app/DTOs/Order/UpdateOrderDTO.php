<?php

declare(strict_types=1);

namespace App\DTOs\Order;

final class UpdateOrderDTO implements UpdateOrderDTOInterface
{
    /**
     * @param array{
     *     manufacturer_id: int|null,
     *     source_id: int|null,
     *     seller_id: int|null,
     *     user_id: int|null,
     *     accepted_date: string|null,
     *     order_date: string|null,
     *     order_time: string|null,
     *     number_external: string|null,
     *     files: array|null,
     *     inspector_id: ?int|null,
     *     phone: string|null,
     *     id: int|null,
     *     items: array<int, array{
     *          id: int|null,
     *          name: string|null,
     *          amount: string|null,
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

    public function id(): ?int
    {
        return $this->attributes['id'] ?? null;
    }

    public function toArray(): array
    {
        $attributes = $this->attributes;

        unset($attributes['id'], $attributes['files']);

        return $attributes;
    }
}