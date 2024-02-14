<?php

declare(strict_types=1);

namespace App\Services\Order\Checker;

interface OrderSellerCheckerInterface
{
    public function check(int $sellerId): bool;
}