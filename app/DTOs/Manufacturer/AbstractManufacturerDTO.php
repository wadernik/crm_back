<?php

declare(strict_types=1);

namespace App\DTOs\Manufacturer;

abstract class AbstractManufacturerDTO implements CreateManufacturerDTOInterface
{
    /**
     * @param array{
     *     name: string,
     *     phone: string|null,
     *     email: string|null,
     *     address: string,
     *     limit: int|null
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

    public function limit(): ?int
    {
        return $this->attributes['limit'] ?? null;
    }
}