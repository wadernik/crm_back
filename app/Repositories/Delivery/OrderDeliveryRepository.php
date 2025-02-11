<?php

declare(strict_types=1);

namespace App\Repositories\Delivery;

use App\Models\Order\Delivery\OrderDelivery;
use App\Repositories\AbstractRepository;
use DB;
use Illuminate\Database\Eloquent\Builder;

final class OrderDeliveryRepository extends AbstractRepository
{
    public function __construct()
    {
        parent::__construct(OrderDelivery::class);
    }

    public function addExtraFilter(Builder $builder, array &$criteria): void
    {
        $this->applyCustomFilter($criteria, $builder);

        unset(
            $criteria['filter']['delivery_date_start'],
            $criteria['filter']['delivery_date_end'],
            $criteria['filter']['delivered'],
        );
    }

    public function deliveriesGroupedByUser(array $criteria): array
    {
        $query = OrderDelivery::query()
            ->select([
                DB::raw('count(id) as amount'),
                DB::raw('sum(delivery_price) as sum_price'),
                'courier_id',
            ])
            ->with('courier:id,first_name,last_name')
            ->groupBy(['courier_id']);

        $this->applyCustomFilter($criteria, $query);

        return $query->get()->toArray();
    }

    private function applyCustomFilter(array $criteria, Builder $query): void
    {
        if (isset($criteria['filter']['delivery_date_start'])) {
            $query->where('order_deliveries.delivery_date', '>=', $criteria['filter']['delivery_date_start']);
        }

        if (isset($criteria['filter']['delivery_date_end'])) {
            $query->where('order_deliveries.delivery_date', '<=', $criteria['filter']['delivery_date_end']);
        }

        if (isset($criteria['filter']['courier_id'])) {
            $query->where('order_deliveries.courier_id', $criteria['filter']['courier_id']);
        }

        if (isset($criteria['filter']['delivered'])) {
            (bool) $criteria['filter']['delivered'] === true
                ? $query->whereNotNull('order_deliveries.delivered_at')
                : $query->whereNull('order_deliveries.delivered_at');
        }
    }
}