<?php

declare(strict_types=1);

namespace App\Models\User\Sub;

interface DeviceInterface
{
    public function id(): int;

    public function name(): string;

    public function lastUsedAt(): string;

    public function current(): bool;
}