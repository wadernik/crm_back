<?php

declare(strict_types=1);

namespace App\Services\Order\Activity;

use App\Models\Comment\CustomComments;
use App\Models\Order\BaseOrder;
use App\Models\Order\Detail\OrderDetail;
use App\Models\Order\File\OrderFile;
use App\Repositories\Activity\ActivityRepositoryInterface;
use App\Repositories\Comment\CommentRepositoryInterface;
use App\Repositories\Order\OrderRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

final class OrderActivityService implements OrderActivityServiceInterface
{
    private OrderRepositoryInterface $orderRepository;
    private CommentRepositoryInterface $commentRepository;

    public function __construct()
    {
        $this->orderRepository = app(OrderRepositoryInterface::class);
        $this->commentRepository = app(CommentRepositoryInterface::class);
    }

    // TODO: refactor
    public function activities(?int $subjectId = null, array $requestParams = []): array
    {
        $total = 0;

        if (!$order = $this->orderRepository->find($subjectId)) {
            return [[], $total];
        }

        // Order
        [$orderActivities, $orderActivitiesTotal] = $this->loadOrderActivities($subjectId);
        $total += $orderActivitiesTotal;

        // Details
        [$detailsActivities, $detailsTotal] = $this->loadDetailActivities($order);
        $total += $detailsTotal;

        // Files
        [$fileActivities, $fileTotal] = $this->loadFileActivities($order);
        $total += $fileTotal;

        // Comments
        [$commentsActivities, $commentTotal] = $this->loadCommentActivities($subjectId);
        $total += $commentTotal;

        $results = collect($orderActivities)
            ->merge($detailsActivities)
            ->merge($fileActivities)
            ->merge($commentsActivities)
            ->sortByDesc(static function ($activity) {
                return Carbon::parse($activity['updated_at'])->timestamp;
            })
            ->values()
            ->toArray();

        return [$results, $total];
    }

    private function loadOrderActivities(?int $subjectId = null): array
    {
        $attributes = ['id', 'event', 'causer_id', 'subject_type', 'properties', 'updated_at'];

        $requestParams['filter']['subject_id'] = $subjectId;
        $requestParams['filter']['subject_type'] = BaseOrder::class;

        // TODO: think about this. every call we need to reinitialize builder state
        $activityRepository = app(ActivityRepositoryInterface::class);

        $orderActivities = $activityRepository->findAllBy($requestParams, $attributes);
        $total = $activityRepository->count($requestParams);

        return [$orderActivities, $total];
    }

    private function loadDetailActivities(Model $order): array
    {
        $attributes = ['id', 'event', 'causer_id', 'subject_type', 'properties', 'updated_at'];

        $requestParams['filter']['subject_id'] = $order['order_detail_id'];
        $requestParams['filter']['subject_type'] = OrderDetail::class;

        $activityRepository = app(ActivityRepositoryInterface::class);

        $detailsActivities = $activityRepository->findAllBy($requestParams, $attributes);

        $total = $activityRepository->count($requestParams);

        return [$detailsActivities, $total];
    }

    private function loadFileActivities(Model $order): array
    {
        $attributes = ['id', 'event', 'causer_id', 'subject_type', 'properties', 'updated_at'];

        // TODO: костыль
        $orderFiles = OrderFile::query()
            ->get(['*'])
            ->where('order_id', $order['id'])
            ->toArray();

        $orderFilesIds = collect($orderFiles)
            ->pluck('id')
            ->toArray();

        $requestParams['filter']['subject_id'] = $orderFilesIds ?: 0;
        $requestParams['filter']['subject_type'] = OrderFile::class;

        $activityRepository = app(ActivityRepositoryInterface::class);

        $fileActivities = $activityRepository->findAllBy($requestParams, $attributes);

        $total = $activityRepository->count($requestParams);

        return [$fileActivities, $total];
    }

    private function loadCommentActivities(?int $subjectId = null): array
    {
        $attributes = ['id', 'event', 'causer_id', 'subject_type', 'properties', 'updated_at'];

        $this->commentRepository->withTrashed();

        $comments = $this->commentRepository->findAllBy(
            ['filter' => ['commentable_type' => BaseOrder::class, 'commentable_id' => $subjectId]]
        );

        $commentsIds = $comments
            ->pluck('id')
            ->toArray();

        unset($requestParams);
        $requestParams['filter']['subject_id'] = $commentsIds;
        $requestParams['filter']['subject_type'] = CustomComments::class;

        $activityRepository = app(ActivityRepositoryInterface::class);

        $commentsActivities = $activityRepository->findAllBy($requestParams, $attributes);

        $total = $activityRepository->count($requestParams);

        return [$commentsActivities, $total];
    }
}