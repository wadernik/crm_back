<?php

declare(strict_types=1);

namespace App\Repositories\OrderComposite;

use App\DTOs\Order\Composite\OrderComposite;
use App\Models\Order\Order;
use App\Repositories\Order\OrderRepositoryInterface;
use Illuminate\Support\Collection;
use function array_map;
use function collect;

final class OrderCompositeRepository implements OrderCompositeRepositoryInterface
{
    public function __construct(private readonly OrderRepositoryInterface $inner)
    {
    }

    public function findAllBy(
        array $criteria,
        array $attributes = ['*'],
        array $sort = [],
        ?string $limit = null,
        ?string $offset = null
    ): Collection
    {
        $this->inner->applyWith(['items', 'items.files:id,filename', 'delivery']);

        $orders = $this->inner->findAllBy($criteria, $attributes, $sort, $limit, $offset);

        return collect(
            array_map(
                static function (Order $order): OrderComposite {
                    $orderComposite = new OrderComposite();

                    $orderComposite->setOrder($order);

                    $orderComposite->setOrderItems(...$order->items);

                    $orderComposite->setDelivery($order->delivery);

                    return $orderComposite;
                },
                $orders->all()
            )
        );
    }

    public function find(int $id): ?OrderComposite
    {
        $order = $this->inner->find($id);

        if (!$order) {
            return null;
        }

        $orderComposite = new OrderComposite();

        $orderComposite->setOrder($order);

        $orderComposite->setOrderItems(...$order->items);

        $orderComposite->setContact($order->contact);

        $orderComposite->setDelivery($order->delivery);

        return $orderComposite;
    }

    public function count(array $criteria): int
    {
        return $this->inner->count($criteria);
    }
}