<?php

declare(strict_types=1);

namespace App\Repositories\Order\Item;

use App\Models\Order\Item\OrderItem;
use App\Repositories\AbstractRepository;
use Illuminate\Database\Eloquent\Builder;

final class OrderItemRepository extends AbstractRepository implements OrderItemRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(OrderItem::class);
    }

    public function addExtraFilter(Builder $builder, array &$criteria): void
    {
        if (isset($criteria['filter']['id'])) {
            is_array($criteria['filter']['id'])
                ? $builder->whereIn('id', $criteria['filter']['id'])
                : $builder->where('id', $criteria['filter']['id']);
        }
    }
}