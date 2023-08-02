<?php

declare(strict_types=1);

namespace App\Repositories\Order;

use App\Models\Order\Order;

interface FindOneByIdInterface
{
    public function find(int $id): ?Order;
}