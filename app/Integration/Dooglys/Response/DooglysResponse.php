<?php

declare(strict_types=1);

namespace App\Integration\Dooglys\Response;

final class DooglysResponse implements DooglysResponseInterface
{
    public function __construct(private readonly bool $status, private readonly array $data)
    {
    }

    public function status(): bool
    {
        return $this->status;
    }

    public function data(): array
    {
        return $this->data;
    }
}