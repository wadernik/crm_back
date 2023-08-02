<?php

declare(strict_types=1);

namespace App\Managers\Order\Normal;

use App\Managers\Order\AbstractOrderManager;

final class OrderManager extends AbstractOrderManager implements OrderManagerInterface
{
    public function __construct()
    {
        parent::__construct(draft: false);
    }
}