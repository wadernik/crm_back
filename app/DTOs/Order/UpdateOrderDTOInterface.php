<?php

declare(strict_types=1);

namespace App\DTOs\Order;

interface UpdateOrderDTOInterface extends CreateOrderDTOInterface
{
    public function id(): ?int;
}