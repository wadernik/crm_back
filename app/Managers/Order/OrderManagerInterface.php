<?php

declare(strict_types=1);

namespace App\Managers\Order;

interface OrderManagerInterface extends OrderCreatorInterface, OrderUpdaterInterface, OrderDeleterInterface
{
}