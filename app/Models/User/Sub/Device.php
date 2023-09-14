<?php

declare(strict_types=1);

namespace App\Models\User\Sub;

final class Device implements DeviceInterface
{
    public function __construct(
        private readonly int $id,
        private readonly string $device,
        private readonly string $lastUsedAt,
        private readonly bool $isCurrent
    )
    {
    }

    public function id(): int
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->device;
    }

    public function lastUsedAt(): string
    {
        return $this->lastUsedAt;
    }

    public function current(): bool
    {
        return $this->isCurrent;
    }
}