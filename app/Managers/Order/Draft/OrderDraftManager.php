<?php

declare(strict_types=1);

namespace App\Managers\Order\Draft;

use App\Managers\Order\AbstractOrderManager;
use App\Models\Order\OrderDraft;

final class OrderDraftManager extends AbstractOrderManager implements OrderDraftManagerInterface
{
    public function __construct()
    {
        parent::__construct(OrderDraft::query());
    }
}