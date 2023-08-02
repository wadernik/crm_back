<?php

declare(strict_types=1);

namespace App\Services\Order\ManagerExtension\Normal;

use App\Managers\Order\Normal\OrderManagerInterface;
use App\Services\Order\ManagerExtension\AbstractOrderUpdaterService;

final class OrderUpdaterService extends AbstractOrderUpdaterService implements OrderUpdaterServiceInterface
{
    public function __construct()
    {
        parent::__construct(OrderManagerInterface::class);
    }
}