<?php

declare(strict_types=1);

namespace App\Repositories\Order;

use App\Models\Order\Order;
use App\Repositories\AbstractRepository;
use App\Repositories\Order\Filter\OrderFilterProcessorInterface;
use Illuminate\Database\Eloquent\Builder;

final class OrderRepository extends AbstractRepository implements OrderRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(Order::class);
    }

    public function addExtraFilter(Builder $builder, array &$criteria): void
    {
        $criteria['filter']['draft'] = false;

        /** @var OrderFilterProcessorInterface $filter */
        $filter = app(OrderFilterProcessorInterface::class);

        $filter->filter($builder, $criteria);

        $criteria['filter'] = [];
    }

    public function find(int $id): ?Order
    {
        return parent::find($id);
    }
}