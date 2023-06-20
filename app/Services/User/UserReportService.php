<?php

declare(strict_types=1);


namespace App\Services\User;

use App\DTOs\User\UserReportDTOInterface;
use App\Models\Order\BaseOrder;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\Collection;

final class UserReportService implements UserReportServiceInterface
{
    private UserRepositoryInterface $userRepository;
    private OrderRepositoryInterface $orderRepository;

    public function __construct()
    {
        $this->userRepository = app(UserRepositoryInterface::class);
        $this->orderRepository = app(OrderRepositoryInterface::class);
    }

    public function report(UserReportDTOInterface $reportDTO): Collection
    {
        $requestDateStart = $reportDTO->dateStart();
        $requestDateEnd = $reportDTO->dateEnd();
        $requestUserId = $reportDTO->userId();
        $requestRoleId = $reportDTO->roleId();

        $ordersFilterParams = array_filter([
            'created_at_start' => $requestDateStart,
            'created_at_end' => $requestDateEnd,
            'status' => (string) BaseOrder::STATUS_SOLD,
        ]);

        $usersFilterParams = array_filter([
            'id' => $requestUserId,
            'role_id' => $requestRoleId,
        ]);

        if (!empty($usersFilterParams)) {
            $usersFilterParams = ['filter' => $usersFilterParams];
        }

        $users = collect($this->userRepository->findAllBy($usersFilterParams));

        $userIds = $users
            ->pluck('id')
            ->toArray();

        $ordersFilterParams['user_id'] = $userIds;

        $users = $users
            ->keyBy('id')
            ->toArray();

        $ordersFilterParams = ['filter' => $ordersFilterParams];

        $orders = $this->orderRepository->findAllBy($ordersFilterParams, ['orders.*']);

        $total = [];

        collect($orders)
            ->groupBy('user_id')
            ->each(function (Collection $orders, int $userId) use ($users, &$total) {
                $totalPrice = $orders->reduce(function ($price, $order) {
                    return $price + $order['price_original'] ?: 0;
                }, 0);

                $firstName = $users[$userId]['first_name'];
                $lastName = $users[$userId]['last_name'];
                $userName = $firstName . ($lastName ? " $lastName" : '');

                $total[$userId] = [
                    'name' => $userName,
                    'total_sold' => $orders->count(),
                    'total_price' => number_format((float) $totalPrice / 100, 2),
                ];
            });

        return collect($total)->values();
    }
}