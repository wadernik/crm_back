<?php

declare(strict_types=1);

namespace App\DTOs\Order;

use Illuminate\Contracts\Support\Arrayable;

interface CreateOrderDTOInterface extends Arrayable
{
    public function main(): array;

    public function items(): array;

    public function contact(): array;
}