<?php

namespace App\Http\Controllers\Api\Reports;

use App\Http\Controllers\Api\AbstractBaseApiController;
use App\Http\Requests\Reports\UsersReportRequest;
use App\Models\BaseOrder;
use App\Services\Orders\OrdersCollectionService;
use App\Services\Users\UsersCollectionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

class UsersReportController extends AbstractBaseApiController
{
    /**
     * @param UsersCollectionService $usersCollectionService
     * @param OrdersCollectionService $ordersCollectionService
     */
    public function __construct(
        private UsersCollectionService $usersCollectionService,
        private OrdersCollectionService $ordersCollectionService
    ) {
    }

    /**
     * @param UsersReportRequest $request
     * @return JsonResponse
     */
    public function index(UsersReportRequest $request): JsonResponse
    {
        if (!$this->isAllowed('reports.view') || !$this->isAllowed('users_report.view')) {
            return $this->responseError(code: Response::HTTP_FORBIDDEN);
        }

        $requestParams = $request->validated()['filter'];

        $requestDateStart = $requestParams['date_start'];
        $requestDateEnd = $requestParams['date_end'] ?? null;
        $requestUserId = $requestParams['user_id'] ?? null;
        $requestRoleId = $requestParams['role_id'] ?? null;

        $ordersFilterParams = array_filter([
            'created_at_start' => $requestDateStart,
            'created_at_end' => $requestDateEnd,
            'status' => BaseOrder::STATUS_SOLD,
        ]);

        $usersFilterParams = array_filter([
            'id' => $requestUserId,
            'role_id' => $requestRoleId,
        ]);

        if (!empty($usersFilterParams)) {
            $usersFilterParams = ['filter' => $usersFilterParams];
        }

        $users = collect($this->usersCollectionService->getInstances(requestParams: $usersFilterParams));

        $userIds = $users
            ->pluck('id')
            ->toArray();
        $ordersFilterParams['user_ids'] = $userIds;

        $users = $users
            ->keyBy('id')
            ->toArray();

        $ordersFilterParams = ['filter' => $ordersFilterParams];

        $orders = $this->ordersCollectionService->getInstances(
            attributes: ['orders.*'],
            requestParams: $ordersFilterParams
        );

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
                    'orders_amount' => $orders->count(),
                    'total_price' => number_format((float) $totalPrice / 100, 2),
                ];
            });

        $total = collect($total)
            ->values()
            ->toArray();

        return $this->responseSuccess(data: $total);
    }
}
