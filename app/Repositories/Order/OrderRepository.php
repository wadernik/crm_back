<?php

declare(strict_types=1);

namespace App\Repositories\Order;

use App\Models\Order\Contact\ContactTypeEnum;
use App\Models\Order\Contact\OrderContact;
use App\Models\Order\Order;
use App\Repositories\AbstractRepository;
use App\Repositories\Order\Filter\OrderFilterProcessorInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Throwable;

final class OrderRepository extends AbstractRepository implements OrderRepositoryInterface
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
        $criteria['filter']['draft'] = false;

        return parent::findAllBy($criteria, $attributes, $sort, $limit, $offset);
    }

    /**
     * @throws Throwable
     */
    public function addExtraFilter(Builder $builder, array &$criteria): void
    {
        if (isset($criteria['filter']['phone'])) {
            $contacts = OrderContact::query()
                ->where('type_id', ContactTypeEnum::idsByValue()[ContactTypeEnum::PHONE->value])
                ->get();

            $orderIds = $contacts
                ->pluck('order_id')
                ->unique()
                ->toArray();

            $criteria['filter']['id'] = $orderIds;

            unset($criteria['filter']['phone']);
        }

        $this->filterProcessor->filter($builder, $criteria);

        $criteria['filter'] = [];
    }

    public function find(int $id): ?Order
    {
        /** @var ?Order $order */
        $order = Order::query()
            ->where('orders.draft', false)
            ->with(['items', 'items.files:id,filename', 'contact'])
            ->find($id);

        return $order;
    }
}