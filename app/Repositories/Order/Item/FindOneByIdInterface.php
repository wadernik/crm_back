<?php

declare(strict_types=1);

namespace App\Repositories\Order\Item;

use App\Models\Order\Item\OrderItem;

interface FindOneByIdInterface
{
    public function find(int $id): ?OrderItem;
}