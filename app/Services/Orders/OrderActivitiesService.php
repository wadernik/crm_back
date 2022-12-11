<?php

declare(strict_types=1);

namespace App\Services\Orders;

use App\Models\BaseOrder;
use App\Models\CustomComments;
use App\Models\OrderDetail;
use App\Models\OrderFile;
use App\Services\Activity\ActivitiesInterface;
use App\Services\Activity\ActivityCollectionService;
use Illuminate\Support\Carbon;

final class OrderActivitiesService implements ActivitiesInterface
{
    private OrderInstanceService $orderInstanceService;
    private ActivityCollectionService $activityCollectionService;

    public function __construct()
    {
        $this->orderInstanceService = app(OrderInstanceService::class);
        $this->activityCollectionService = app(ActivityCollectionService::class);
    }

    public function getActivities(?int $subjectId = null, array $requestParams = []): array
    {
        $attributes = ['orders.id', 'order_details.id AS order_detail_id'];

        // Order
        $order = $this->orderInstanceService->getInstance(
            id: $subjectId,
            attributes: $attributes,
        );

        if (!$order) {
            return [[], 0];
        }

        $attributes = ['id', 'event', 'causer_id', 'subject_type', 'properties', 'updated_at'];

        $requestParams['filter']['subject_id'] = $subjectId;
        $requestParams['filter']['subject'] = BaseOrder::class;

        $orderActivities = $this->activityCollectionService->getInstances(
            attributes: $attributes,
            requestParams: $requestParams
        );

        // Details
        $requestParams['filter']['subject_id'] = $order['order_detail_id'];
        $requestParams['filter']['subject'] = OrderDetail::class;

        $detailsActivities = $this->activityCollectionService->getInstances(
            attributes: $attributes,
            requestParams: $requestParams
        );

        // Comments
        $comments = CustomComments::query()
            ->where('commentable_type', BaseOrder::class)
            ->where('commentable_id', $subjectId)
            ->get()
            ->toArray();

        $commentsIds = collect($comments)
            ->pluck('id')
            ->toArray();

        unset($requestParams['filter']['subject_id']);
        $requestParams['filter']['subject_ids'] = $commentsIds;
        $requestParams['filter']['subject'] = CustomComments::class;

        $commentsActivities = $this->activityCollectionService->getInstances(
            attributes: $attributes,
            requestParams: $requestParams
        );

        // TODO: костыль
        $orderFiles = OrderFile::query()
            ->get(['*'])
            ->where('order_id', $order['id'])
            ->toArray();

        $orderFilesIds = collect($orderFiles)
            ->pluck('id')
            ->toArray();

        unset($requestParams['filter']['subject_id']);

        $requestParams['filter']['subject_ids'] = $orderFilesIds;
        $requestParams['filter']['subject'] = OrderFile::class;

        $fileActivities = $this->activityCollectionService->getInstances(
            attributes: $attributes,
            requestParams: $requestParams
        );

        $results = collect($orderActivities)
            ->merge($detailsActivities)
            ->merge($fileActivities)
            ->merge($commentsActivities)
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