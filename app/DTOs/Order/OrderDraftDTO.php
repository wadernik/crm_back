<?php

declare(strict_types=1);

namespace App\DTOs\Order;

final class OrderDraftDTO implements OrderDraftDTOInterface
{
    /**
     * @param array{
     *     name: string|null,
     *     amount: string|null,
     *     label: string|null,
     *     comment: string|null,
     *     decoration: string|null,
     *     accepted_date: string|null,
     *     order_date: string|null,
     *     order_time: string|null,
     *     number_external: string|null,
     *     manufacturer_id: int|null,
     *     source_id: int|null,
     *     seller_id: int|null,
     *     files: array|null,
     *     id: int|null,
     *     status: int|null
     * } $attributes
     */
    public function __construct(private array $attributes)
    {
    }

    public function amount(): ?string
    {
        return $this->attributes['amount'] ?? null;
    }

    public function comment(): ?string
    {
        return $this->attributes['comment'] ?? null;
    }

    public function decoration(): ?string
    {
        return $this->attributes['decoration'] ?? null;
    }

    public function acceptedDate(): ?string
    {
        return $this->attributes['accepted_date'] ?? null;
    }

    public function orderDate(): ?string
    {
        return $this->attributes['order_date'] ?? null;
    }

    public function orderTime(): ?string
    {
        return $this->attributes['order_time'] ?? null;
    }

    public function numberExternal(): ?string
    {
        return $this->attributes['number_external'] ?? null;
    }

    public function manufacturerId(): ?int
    {
        return $this->attributes['manufacturer_id'] ?? null;
    }

    public function sourceId(): ?int
    {
        return $this->attributes['source_id'] ?? null;
    }

    public function sellerId(): ?int
    {
        return $this->attributes['seller_id'] ?? null;
    }

    public function files(): array
    {
        return $this->attributes['files'] ?? [];
    }

    public function name(): ?string
    {
        return $this->attributes['name'] ?? null;
    }

    public function label(): ?string
    {
        return $this->attributes['label'] ?? null;
    }

    public function orderId(): ?int
    {
        return $this->attributes['id'] ?? null;
    }

    public function status(): ?int
    {
        return $this->attributes['status'] ?? null;
    }

    public function toArray(): array
    {
        unset($this->attributes['id']);

        return $this->attributes;
    }
}