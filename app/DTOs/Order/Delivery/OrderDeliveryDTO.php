<?php

declare(strict_types=1);

namespace App\DTOs\Order\Delivery;

final class OrderDeliveryDTO
{
    public function __construct(private readonly array $attributes)
    {
    }

    public function courierId(): ?int
    {
        return $this->attributes['courier_id'] ?? null;
    }

    public function address(): ?string
    {
        return $this->attributes['address'] ?? null;
    }

    public function deliveryPrice(): ?int
    {
        return (int) ($this->attributes['delivery_price'] ?? null);
    }

    public function deliveryDate(): ?string
    {
        return $this->attributes['delivery_date'] ?? null;
    }

    public function delivered(): ?bool
    {
        return $this->attributes['delivered'] ?? false;
    }
}