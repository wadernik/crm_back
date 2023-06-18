<?php

declare(strict_types=1);

namespace App\Services\Order\Dooglys;

interface DooglysServiceInterface
{
    public function finalPrice(string $date, string $number): int;
}