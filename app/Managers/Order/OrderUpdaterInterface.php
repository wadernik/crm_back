<?php

declare(strict_types=1);

namespace App\Managers\Order;

use App\DTOs\Order\OrderDraftDTOInterface;
use App\DTOs\Order\UpdateOrderDTOInterface;
use Illuminate\Database\Eloquent\Model;

interface OrderUpdaterInterface
{
    // TODO: refactor arguments
    public function update(int $id, UpdateOrderDTOInterface|OrderDraftDTOInterface $orderDTO): ?Model;
}