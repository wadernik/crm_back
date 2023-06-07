<?php

declare(strict_types=1);

namespace App\Services\Order\Draft;

use App\Managers\Order\Draft\OrderDraftManagerInterface;
use App\Services\Order\AbstractOrderCreatorService;

final class OrderDraftCreatorService extends AbstractOrderCreatorService implements OrderDraftCreatorServiceInterface
{
    public function __construct()
    {
        parent::__construct(OrderDraftManagerInterface::class);
    }
}