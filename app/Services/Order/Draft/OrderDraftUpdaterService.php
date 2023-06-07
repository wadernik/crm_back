<?php

declare(strict_types=1);

namespace App\Services\Order\Draft;

use App\Managers\Order\Draft\OrderDraftManagerInterface;
use App\Services\Order\AbstractOrderUpdaterService;

final class OrderDraftUpdaterService extends AbstractOrderUpdaterService implements OrderDraftUpdaterServiceInterface
{
    public function __construct()
    {
        parent::__construct(OrderDraftManagerInterface::class);
    }
}