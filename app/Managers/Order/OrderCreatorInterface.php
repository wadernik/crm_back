<?php

declare(strict_types=1);

namespace App\Managers\Order;

use App\DTOs\Order\CreateOrderDTOInterface;
use App\DTOs\Order\OrderDraftDTOInterface;
use Illuminate\Database\Eloquent\Model;

interface OrderCreatorInterface
{
    // TODO: refactor arguments
    public function create(CreateOrderDTOInterface|OrderDraftDTOInterface $orderDTO): Model;
}