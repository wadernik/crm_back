<?php

declare(strict_types=1);

namespace App\Services\Order;

use Illuminate\Database\Eloquent\Model;

interface OrderUpdaterServiceInterface
{
    public function update(int $id, array $attributes): ?Model;
}