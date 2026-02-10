<?php

declare(strict_types=1);

namespace App\Services\Order\Report;

use App\DTOs\Order\Composite\OrderComposite;
use App\DTOs\Report\AcceptedOrderItemDto;
use App\DTOs\Report\AcceptedOrdersRequestDtoInterface;
use App\DTOs\Report\AcceptedOrderDto;
use App\Models\Dictionary\Dictionary;
use App\Models\Dictionary\DictionaryTypeEnum;
use App\Models\Order\Item\OrderItem;
use App\Models\Order\Item\OrderItemInterface;
use App\Models\Order\OrderStatus;
use App\Models\Seller\Seller;
use App\Models\Unit\UnitEnum;
use App\Models\User\User;
use App\Repositories\Dictionary\DictionaryRepositoryInterface;
use App\Repositories\OrderComposite\OrderCompositeRepositoryInterface;
use App\Repositories\Seller\SellerRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;

final class AcceptedOrdersReporterService
{
    public function __construct(
        private readonly OrderCompositeRepositoryInterface $orderRepository,
        private readonly UserRepositoryInterface $userRepository,
        private readonly SellerRepositoryInterface $sellerRepository,
        private readonly DictionaryRepositoryInterface $dictionaryRepository
    ) {
    }

    public function get(AcceptedOrdersRequestDtoInterface $dto): Collection
    {
        $requestDateStart = $dto->dateStart();
        $requestDateEnd = $dto->dateEnd();

        $ordersFilterParams = array_filter([
            'created_at_start' => $requestDateStart,
            'created_at_end' => $requestDateEnd,
        ]);

        /** @var Collection<OrderComposite> $orders */
        $orders = $this->orderRepository->findAllBy(['filter' => $ordersFilterParams], ['orders.*']);

        $userIds = [];
        $sellerIds = [];
        $titleIds = [];

        foreach ($orders as $order) {
            $userIds[$order->order()->user_id] = true;

            if ($order->order()->inspector_id) {
                $userIds[$order->order()->inspector_id] = true;
            }

            if ($order->order()->seller_id) {
                $sellerIds[$order->order()->seller_id] = true;
            }

            if ($order->order()->source_id) {
                $sellerIds[$order->order()->source_id] = true;
            }

            /** @var OrderItem $item */
            foreach ($order->items() as $item) {
                $titleIds[$item->title_id] = true;
            }
        }

        /** @var User[] $users */
        $users = $this->userRepository
            ->findByIds(...array_keys($userIds))
            ->keyBy('id');

        /** @var Seller[] $sellers */
        $sellers = $this->sellerRepository
            ->findAllBy(['filter' => ['id' => array_keys($sellerIds)]])
            ->keyBy('id');

        /** @var Collection<Dictionary> $titles */
        $titles = $this->dictionaryRepository
            ->findAllBy(['filter' => [
                'type' => DictionaryTypeEnum::PRODUCT_TITLE->value,
                'ids' => array_keys($titleIds),
            ]])
            ->keyBy('id');

        return $orders
            ->map(function (OrderComposite $orderComposite) use ($users, $sellers, $titles): AcceptedOrderDto {
                $order = $orderComposite->order();
                $seller = $sellers[$order->seller_id] ?? null;
                $source = $sellers[$order->source_id] ?? null;
                $user = $users[$order->user_id] ?? null;
                $inspector = $users[$order->inspector_id] ?? null;

                return new AcceptedOrderDto(
                    id: $order->id,
                    number: $order->number,
                    status: OrderStatus::statusCaptions()[$order->status] ?? $order->status,
                    source: $source?->name,
                    buyer: implode(' ', [$user?->first_name, $user?->last_name]),
                    acceptedDate: CarbonImmutable::parse($order->accepted_date)->format('d.m.Y'),
                    orderDate: CarbonImmutable::parse($order->order_date)->format('d.m.Y'),
                    orderTime: $order->order_time->format('H:i'),
                    price: $order->price,
                    items: $order->items
                        ->map(
                            static function (OrderItemInterface $item) use ($titles): AcceptedOrderItemDto {
                                return new AcceptedOrderItemDto(
                                    title: isset($titles[$item->title_id]) ? $titles[$item->title_id]->value : '',
                                    unit: UnitEnum::shorts()[$item->unit_id] ?? $item->unit_id,
                                    amount: $item->amount,
                                );
                            }
                        ),
                    seller: $seller?->name,
                    numberExternal: $order->number_external,
                    inspector: $inspector?->first_name . ' ' . $inspector?->last_name,
                    phone: $order->phone
                );
            });
    }
}
