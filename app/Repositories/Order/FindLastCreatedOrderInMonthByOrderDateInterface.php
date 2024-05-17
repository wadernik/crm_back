<?php

declare(strict_types=1);

namespace App\Repositories\Order;

use App\Models\Order\Order;

interface FindLastCreatedOrderInMonthByOrderDateInterface
{
    public function findLastOrderByOrderDate(string $date): ?Order;
}