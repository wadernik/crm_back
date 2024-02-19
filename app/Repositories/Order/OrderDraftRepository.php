<?php

declare(strict_types=1);

namespace App\Repositories\Order;

use App\Models\Order\Order;
use App\Repositories\AbstractRepository;
use App\Repositories\Order\Filter\OrderFilterProcessorInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Throwable;

final class OrderDraftRepository extends AbstractRepository implements OrderDraftRepositoryInterface
{
    public function __construct(private readonly OrderFilterProcessorInterface $filterProcessor)
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
        $criteria['filter']['draft'] = true;

        return parent::findAllBy($criteria, $attributes, $sort, $limit, $offset);
    }

    /**
     * @throws Throwable
     */
    public function addExtraFilter(Builder $builder, array &$criteria): void
    {
        $this->filterProcessor->filter($builder, $criteria);

        $criteria['filter'] = [];
    }

    public function find(int $id): ?Order
    {
        /** @var ?Order $order */
        $order = Order::query()
            ->where('orders.draft', true)
            ->with(['items', 'items.files:id,filename', 'contact'])
            ->find($id);

        return $order;
    }

    public function count(array $criteria): int
    {
        $criteria['filter']['draft'] = true;

        return parent::count($criteria);
    }
}