<?php

declare(strict_types=1);

namespace App\Managers\Order;

interface AbstractOrderManagerInterface extends OrderCreatorInterface, OrderUpdaterInterface, OrderDeleterInterface
{
}