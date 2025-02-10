<?php

declare(strict_types=1);

namespace App\Models\Order\Delivery;

/**
 * @property int    $id
 * @property int    $order_id
 * @property int    $courier_id
 * @property string $address
 * @property int    delivery_price
 */
interface OrderDeliveryInterface
{
}