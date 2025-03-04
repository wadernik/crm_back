<?php

declare(strict_types=1);

namespace App\Processor\Delivery;

use App\DTOs\Order\Composite\OrderComposite;
use App\Exceptions\OrderDoesNotHaveDeliveryException;
use App\Models\Order\Order;
use App\Models\User\User;
use App\Repositories\User\UserRepositoryInterface;

use function config;

final class OrderDeliveryCourierSetterProcessor
{
    public function __construct(private readonly UserRepositoryInterface $userRepository)
    {
    }

    /**
     * @throws OrderDoesNotHaveDeliveryException
     */
    public function setCourier(Order $order, User $courier): void
    {
        $delivery = $order->delivery;

        if (!$delivery) {
            throw new OrderDoesNotHaveDeliveryException();
        }

        // if ($delivery->courier_id) {
        //     throw new OrderAlreadyHasCourier();
        // }

        $delivery->courier_id = $courier->id;

        $delivery->save();
    }

    public function addCourierToDeliveryIfNotSet(OrderComposite $orderComposite): void
    {
        if (!$orderComposite->delivery()) {
            return;
        }

        if ($orderComposite->delivery()->courier_id) {
            return;
        }

        $filter = [
            'filter' => [
                'first_name' => config('replacement.courier.first_name'),
                'last_name' => config('replacement.courier.last_name'),
            ]
        ];

        $courier = $this->userRepository->findAllBy($filter)->first();

        if ($courier) {
            $orderComposite->delivery()->courier_id = $courier->id;
        }
    }
}