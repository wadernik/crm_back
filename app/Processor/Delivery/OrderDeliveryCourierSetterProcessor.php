<?php

declare(strict_types=1);

namespace App\Processor\Delivery;

use App\Exceptions\OrderDoesNotHaveDeliveryException;
use App\Models\Order\Order;
use App\Models\User\User;

final class OrderDeliveryCourierSetterProcessor
{
    /**
     * @throws OrderDoesNotHaveDeliveryException
     */
    public function setCourier(Order $order, User $courier): void
    {
        $delivery = $order->delivery;

        if (!$delivery) {
            throw new OrderDoesNotHaveDeliveryException();
        }

        // if ($delivery->courier_id) {
        //     throw new OrderAlreadyHasCourier();
        // }

        $delivery->courier_id = $courier->id;

        $delivery->save();
    }
}