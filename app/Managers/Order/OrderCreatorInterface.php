<?php

declare(strict_types=1);

namespace App\Managers\Order;

use App\DTOs\Order\CreateOrderDTOInterface;
use Illuminate\Database\Eloquent\Model;

interface OrderCreatorInterface
{
    public function create(CreateOrderDTOInterface $orderDTO): Model;
}