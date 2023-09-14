<?php

declare(strict_types=1);

namespace App\DTOs\ManufacturerDateLimit;

final class CreateDateLimitDTO implements CreateDateLimitDTOInterface
{
    /**
     * @param array{
     *     manufacturer_id: int,
     *     date: string|null,
     *     dates: array|null,
     *     limit_type: int
     * } $attributes
     */
    public function __construct(private readonly array $attributes)
    {
    }

    public function manufacturerId(): int
    {
        return $this->attributes['manufacturer_id'];
    }

    public function date(): ?string
    {
        return $this->attributes['date'] ?? null;
    }

    public function dates(): ?array
    {
        return $this->attributes['dates'] ?? null;
    }

    public function limitType(): int
    {
        return $this->attributes['limit_type'];
    }

    public function toArray(): array
    {
        return $this->attributes;
    }
}