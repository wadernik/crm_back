<?php

declare(strict_types=1);

namespace App\Repositories\Order;

use App\Models\Order\Order;
use App\Repositories\AbstractRepository;
use App\Repositories\Order\Filter\OrderFilterProcessorInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Throwable;

final class OrderRepository extends AbstractRepository implements OrderRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(Order::class);
    }

    public function findAllBy(
        array $criteria,
        array $attributes = ['*'],
        array $sort = [],
        ?string $limit = null,
        ?string $offset = null
    ): Collection
    {
        $criteria['filter']['draft'] = false;

        return parent::findAllBy($criteria, $attributes, $sort, $limit, $offset);
    }

    /**
     * @throws Throwable
     */
    public function addExtraFilter(Builder $builder, array &$criteria): void
    {
        /** @var OrderFilterProcessorInterface $filter */
        $filter = app(OrderFilterProcessorInterface::class);

        $filter->filter($builder, $criteria);

        $criteria['filter'] = [];
    }

    public function find(int $id): ?Order
    {
        /** @var ?Order $order */
        $order = Order::query()
            ->where('orders.draft', false)
            ->with(['items', 'files:id,filename'])
            ->find($id);

        return $order;
    }
}