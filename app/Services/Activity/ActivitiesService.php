<?php

declare(strict_types=1);

namespace App\Services\Activity;

use App\Models\BaseOrder;
use App\Models\OrderDetail;
use App\Services\Orders\OrderInstanceService;
use Illuminate\Support\Carbon;

final class ActivitiesService
{
    private OrderInstanceService $orderInstanceService;
    private ActivityCollectionService $activityCollectionService;

    public function __construct()
    {
        $this->orderInstanceService = app(OrderInstanceService::class);
        $this->activityCollectionService = app(ActivityCollectionService::class);
    }

    public function getActivities(int $orderId, array $requestParams = []): array
    {
        $attributes = ['orders.id', 'order_details.id AS order_detail_id'];

        $order = $this->orderInstanceService->getInstance($orderId, attributes: $attributes);

        if (!$order) {
            return [[], 0];
        }

        $attributes = ['id', 'event', 'causer_id', 'properties', 'updated_at'];

        $requestParams['filter']['subject_id'] = $orderId;
        $requestParams['filter']['subject'] = BaseOrder::class;

        $orderActivities = $this->activityCollectionService->getInstances(
            attributes: $attributes,
            requestParams: $requestParams
        );

        $requestParams['filter']['subject_id'] = $order['order_detail_id'];
        $requestParams['filter']['subject'] = OrderDetail::class;

        $detailsActivities = $this->activityCollectionService->getInstances(
            attributes: $attributes,
            requestParams: $requestParams
        );

        $results = collect($orderActivities)
            ->merge($detailsActivities)
            ->sortByDesc(static function ($activity) {
                return Carbon::parse($activity['updated_at'])->timestamp;
            })
            ->values()
            ->toArray();

        $total = $this->activityCollectionService->countInstances($requestParams);
        $total += $this->activityCollectionService->countInstances($requestParams);

        return [$results, $total];
    }
}