<?php

declare(strict_types=1);

namespace App\DTOs\Order;

final class OrderCounterFilterDTO
{
    /**
     * @param array{
     *     date_start: string|null,
     *     date_end: string|null
     * } $filter
     */
    public function __construct(private readonly array $filter, private readonly int $userId)
    {
    }

    public function getDateStart(): ?string
    {
        return $this->filter['date_start'] ?? null;
    }

    public function getDateEnd(): ?string
    {
        return $this->filter['date_end'] ?? null;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }
}