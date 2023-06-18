<?php

declare(strict_types=1);

namespace App\DTOs\Order;

final class CreateOrderDTO implements CreateOrderDTOInterface
{
    /**
     * @param array{
     *     name: string,
     *     amount: string,
     *     label: string|null,
     *     comment: string|null,
     *     decoration: string|null,
     *     accepted_date: string,
     *     order_date: string,
     *     order_time: string,
     *     number_external: string,
     *     manufacturer_id: int,
     *     source_id: int,
     *     seller_id: int,
     *     user_id: int,
     *     files: array|null
     * } $attributes
     */
    public function __construct(private array $attributes)
    {
    }

    public function amount(): string
    {
        return $this->attributes['amount'];
    }

    public function comment(): ?string
    {
        return $this->attributes['comment'] ?? null;
    }

    public function decoration(): ?string
    {
        return $this->attributes['decoration'] ?? null;
    }

    public function acceptedDate(): string
    {
        return $this->attributes['accepted_date'];
    }

    public function orderDate(): string
    {
        return $this->attributes['order_date'];
    }

    public function orderTime(): string
    {
        return $this->attributes['order_time'];
    }

    public function numberExternal(): ?string
    {
        return $this->attributes['number_external'] ?? null;
    }

    public function manufacturerId(): int
    {
        return $this->attributes['manufacturer_id'];
    }

    public function sourceId(): int
    {
        return $this->attributes['source_id'];
    }

    public function sellerId(): int
    {
        return $this->attributes['seller_id'];
    }

    public function userId(): int
    {
        return $this->attributes['user_id'];
    }

    public function files(): array
    {
        return $this->attributes['files'] ?? [];
    }

    public function name(): string
    {
        return $this->attributes['name'];
    }

    public function label(): ?string
    {
        return $this->attributes['label'] ?? null;
    }

    public function toArray(): array
    {
        unset($this->attributes['files']);

        return $this->attributes;
    }
}