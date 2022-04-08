<?php

namespace App\Services;

use App\Services\Traits\Filterable;

class OrdersService
{
    use Filterable;

    public function getOrder()
    {
    }

    public function getOrders()
    {
    }

    public function createOrder()
    {
    }

    public function proccessOrder()
    {
    }

    public function approveOrder()
    {
    }

    /**
     * Manufacturers can fulfill a limited amount of orders per day.
     * A function checks if it can take an order for a specific date.
     */
    public function checkOrdersLimit()
    {
    }
}
