<?php

declare(strict_types=1);

namespace App\Repositories\Order;

use App\Models\Order\Order;

interface FindOneByIdTrashedInterface
{
    public function findIncludingTrashed(int $id): ?Order;
}