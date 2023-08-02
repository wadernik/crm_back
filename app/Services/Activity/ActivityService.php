<?php

declare(strict_types=1);

namespace App\Services\Activity;

use App\Models\Order\Item\OrderItem;
use App\Models\Order\Order;
use App\Repositories\Activity\ActivityRepositoryInterface;
use App\Repositories\Order\Item\OrderItemRepositoryInterface;
use Illuminate\Support\Collection;

final class ActivityService implements ActivityServiceInterface
{
    private ActivityRepositoryInterface $repository;

    public function __construct()
    {
        $this->repository = app(ActivityRepositoryInterface::class);
    }

    public function activities(?int $subjectId = null, array $requestParams = []): array
    {
        if ($subjectId) {
            $requestParams['filter']['subject_id'] = $subjectId;
        }

        $requestParams['filter']['detail'] = true;

        $attributes = ['id', 'event', 'causer_id', 'subject_type', 'subject_id', 'properties', 'created_at'];

        $sort = ['sort' => $requestParams['sort'] ?? 'created_at', 'order' => $requestParams['order'] ?? 'desc'];
        $limit = $requestParams['limit'] ?? null;
        $offset = $requestParams['page'] ?? null;

        $activities = ($this->repository->findAllBy($requestParams, $attributes, $sort, $limit, $offset));

        $activities = $this->remapForOrderItems($activities);

        $total = $this->repository->count($requestParams);

        return [$activities->toArray(), $total];
    }

    private function remapForOrderItems(Collection $activities): Collection
    {
        $itemIds = [];

        foreach ($activities as $activity) {
            if ($activity->subject_type === OrderItem::class) {
                $itemIds[$activity->subject_id] = true;
            }
        }

        /** @var OrderItemRepositoryInterface $itemsRepository */
        $itemsRepository = app(OrderItemRepositoryInterface::class);

        $items = $itemsRepository->findAllBy(['filter' => ['id' => array_keys($itemIds)]]);

        $items = $items->keyBy('id');

        foreach ($activities as $activity) {
            if ($activity->subject_type === OrderItem::class) {
                $activity->subject_id = $items[$activity->subject_id]->order_id;
            }
        }

        return $activities;
    }
}