<?php

declare(strict_types=1);

namespace App\Integration\Dooglys\Response;

interface DooglysResponseInterface
{
    public function status(): bool;

    public function data(): array;
}