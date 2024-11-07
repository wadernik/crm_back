<?php

declare(strict_types=1);

namespace App\DTOs\Seller;

abstract class AbstractSellerDTO
{
    /**
     * @param array{
     *     name: string,
     *     phone: string|null,
     *     email: string|null,
     *     address: string,
     *     working_hours: string|null,
     *     created_by: int|null
     * } $attributes
     */
    public function __construct(private array $attributes)
    {
    }

    public function name(): string
    {
        return $this->attributes['name'];
    }

    public function phone(): ?string
    {
        return $this->attributes['phone'] ?? null;
    }

    public function email(): ?string
    {
        return $this->attributes['email'] ?? null;
    }

    public function address(): string
    {
        return $this->attributes['address'];
    }

    public function workingHours(): ?string
    {
        return $this->attributes['working_hours'] ?? null;
    }

    public function createdBy(): ?int
    {
        return $this->attributes['created_by'] ?? null;
    }

    public function setCreatedBy(?int $createdBy = null): void
    {
        $this->attributes['created_by'] = $createdBy;
    }

    public function toArray(): array
    {
        $attributes = $this->attributes;

        $attributes['short_address'] = $this->address();
        unset($attributes['address']);

        return $attributes;
    }
}
