<?php

declare(strict_types=1);

namespace App\Services\Order\Dooglys\Sub;

final class DooglysResponse implements DooglysResponseInterface
{
    public function __construct(private readonly bool $status, private readonly array $orders)
    {
    }

    public function status(): bool
    {
        return $this->status;
    }

    public function orders(): array
    {
        return $this->orders;
    }
}