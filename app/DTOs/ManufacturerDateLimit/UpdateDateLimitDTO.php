<?php

declare(strict_types=1);

namespace App\DTOs\ManufacturerDateLimit;

final class UpdateDateLimitDTO implements UpdateDateLimitDTOInterface
{
    /**
     * @param array{
     *     manufacturer_id: int|null,
     *     date: string|null,
     *     limit_type: int|null
     * } $attributes
     */
    public function __construct(private readonly array $attributes)
    {
    }

    public function manufacturerId(): ?int
    {
        return $this->attributes['manufacturer_id'] ?? null;
    }

    public function date(): ?string
    {
        return $this->attributes['date'] ?? null;
    }

    public function limitType(): ?int
    {
        return $this->attributes['limit_type'] ?? null;
    }

    public function toArray(): array
    {
        return $this->attributes;
    }
}