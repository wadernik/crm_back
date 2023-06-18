<?php

declare(strict_types=1);

namespace App\Services\Order\Dooglys\Sub;

interface DooglysResponseInterface
{
    public function status(): bool;

    public function orders(): array;
}