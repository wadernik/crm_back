<?php

declare(strict_types=1);

namespace App\DTOs\Report;

final class AcceptedOrdersRequestDto implements AcceptedOrdersRequestDtoInterface
{
    /**
     * @param array{
     *     filter: array{
     *          date_start: string,
     *          date_end: string|null
     *     }
     * } $attributes
     */
    public function __construct(private readonly array $attributes)
    {
    }

    public function dateStart(): string
    {
        return $this->attributes['filter']['date_start'];
    }

    public function dateEnd(): ?string
    {
        return $this->attributes['filter']['date_end'] ?? null;
    }

    public function toArray(): array
    {
        return $this->attributes;
    }
}
