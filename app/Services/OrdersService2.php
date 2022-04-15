<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderDetail;

class OrdersService2 extends BaseOrdersService
{
    public function __construct()
    {
        $this->orderClass = new Order();
        $this->orderDetailClass = new OrderDetail();
    }
}
