<?php

declare(strict_types=1);

namespace App\Services\Order\ManagerExtension\Draft;

use App\DTOs\Order\OrderDraftDTO;
use App\Managers\Order\Draft\OrderDraftManagerInterface;
use App\Services\Order\ManagerExtension\AbstractOrderUpdaterService;

final class OrderDraftUpdaterService extends AbstractOrderUpdaterService implements OrderDraftUpdaterServiceInterface
{
    public function __construct()
    {
        parent::__construct(OrderDraftManagerInterface::class, OrderDraftDTO::class);
    }
}