<?php

declare(strict_types=1);

namespace App\Services\Order;

use Illuminate\Database\Eloquent\Model;

interface OrderCreatorServiceInterface
{
    public function create(array $attributes): Model;
}