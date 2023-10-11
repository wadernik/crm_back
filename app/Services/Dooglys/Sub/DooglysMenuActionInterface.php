<?php

declare(strict_types=1);

namespace App\Services\Dooglys\Sub;

interface DooglysMenuActionInterface
{
    public function menu(string $uuid): array;
}