<?php

declare(strict_types=1);

namespace App\Services\Order\Activity;

use App\Models\Comment\Comment;
use App\Models\Order\File\OrderFile;
use App\Models\Order\Item\OrderItem;
use App\Models\Order\Order;
use App\Repositories\Activity\ActivityRepositoryInterface;
use App\Repositories\Comment\CommentRepositoryInterface;
use App\Repositories\Order\OrderRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use function App\Helpers\Functions\load_service;
use function collect;

final class OrderActivityService implements OrderActivityServiceInterface
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly CommentRepositoryInterface $commentRepository
    )
    {
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

        // Items
        [$itemsActivities, $itemsTotal] = $this->loadItemsActivities($order);
        $total += $itemsTotal;

        // Files
        [$fileActivities, $fileTotal] = $this->loadFileActivities($order);
        $total += $fileTotal;

        // Comments
        [$commentsActivities, $commentTotal] = $this->loadCommentActivities($subjectId);
        $total += $commentTotal;

        $results = collect($orderActivities)
            ->merge($itemsActivities)
            ->merge($fileActivities)
            ->merge($commentsActivities)
            ->sortByDesc(static function ($activity) {
                return Carbon::parse($activity['created_at'])->timestamp;
            })
            ->values()
            ->toArray();

        return [$results, $total];
    }

    private function loadOrderActivities(?int $subjectId = null): array
    {
        $attributes = ['id', 'event', 'causer_id', 'subject_type', 'subject_id', 'properties', 'created_at'];

        $requestParams['filter']['subject_id'] = $subjectId;
        $requestParams['filter']['subject_type'] = Order::class;

        // TODO: think about this. every call we need to reinitialize builder state
        $activityRepository = load_service(ActivityRepositoryInterface::class);

        $orderActivities = $activityRepository->findAllBy($requestParams, $attributes);
        $total = $activityRepository->count($requestParams);

        return [$orderActivities, $total];
    }

    private function loadItemsActivities(Order $order): array
    {
        $attributes = ['id', 'event', 'causer_id', 'subject_type', 'subject_id', 'properties', 'created_at'];

        $itemsIds = $order->items
            ->pluck('id')
            ->toArray();

        $requestParams['filter']['subject_id'] = $itemsIds;
        $requestParams['filter']['subject_type'] = OrderItem::class;

        $activityRepository = load_service(ActivityRepositoryInterface::class);

        $itemsActivities = $activityRepository->findAllBy($requestParams, $attributes);

        foreach ($itemsActivities as $item) {
            $item->subject_id = $order->id;
        }

        $total = $activityRepository->count($requestParams);

        return [$itemsActivities, $total];
    }

    private function loadFileActivities(Model $order): array
    {
        $attributes = ['id', 'event', 'causer_id', 'subject_type', 'subject_id', 'properties', 'created_at'];

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

        $activityRepository = load_service(ActivityRepositoryInterface::class);

        $fileActivities = $activityRepository->findAllBy($requestParams, $attributes);

        $total = $activityRepository->count($requestParams);

        return [$fileActivities, $total];
    }

    private function loadCommentActivities(?int $subjectId = null): array
    {
        $attributes = ['id', 'event', 'causer_id', 'subject_type', 'subject_id', 'properties', 'created_at'];

        $this->commentRepository->withTrashed();

        $comments = $this->commentRepository->findAllBy(
            ['filter' => ['commentable_type' => Order::class, 'commentable_id' => $subjectId]]
        );

        $commentsIds = $comments
            ->pluck('id')
            ->toArray();

        unset($requestParams);
        $requestParams['filter']['subject_id'] = $commentsIds;
        $requestParams['filter']['subject_type'] = Comment::class;

        $activityRepository = load_service(ActivityRepositoryInterface::class);

        $commentsActivities = $activityRepository->findAllBy($requestParams, $attributes);

        $total = $activityRepository->count($requestParams);

        return [$commentsActivities, $total];
    }
}