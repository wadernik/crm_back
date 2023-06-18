<?php

declare(strict_types=1);

namespace App\Repositories\Order\Draft;

use App\Models\Order\OrderDraft;
use App\Repositories\Order\AbstractOrderRepository;
use App\Repositories\Order\OrderFilter;

final class OrderDraftRepository extends AbstractOrderRepository implements OrderDraftRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(OrderDraft::class, OrderFilter::class);
    }
}