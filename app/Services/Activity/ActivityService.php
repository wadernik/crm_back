<?php

declare(strict_types=1);

namespace App\Services\Activity;

use App\Models\Activity\ActivityInterface;
use App\Models\Comment\CustomComments;
use App\Models\Order\BaseOrder;
use App\Models\Order\Detail\OrderDetail;
use App\Models\Order\File\OrderFile;
use App\Repositories\Activity\ActivityRepositoryInterface;

final class ActivityService implements ActivityServiceInterface
{
    private ActivityRepositoryInterface $repository;

    private const WHITELISTED_CLASSES = [
        OrderDetail::class => true,
        OrderFile::class => true,
        CustomComments::class => true,
    ];

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

        $attributes = ['id', 'event', 'causer_id', 'subject_type', 'properties', 'created_at'];

        $sort = ['sort' => $requestParams['sort'] ?? 'created_at', 'order' => $requestParams['order'] ?? 'desc'];
        $limit = $requestParams['limit'] ?? null;
        $offset = $requestParams['page'] ?? null;

        $activities = ($this->repository->findAllBy($requestParams, $attributes, $sort, $limit, $offset));

        $total = $this->repository->count($requestParams);

        $result = $activities
            ->map(static function (ActivityInterface $activity) {
                if (isset(self::WHITELISTED_CLASSES[$activity->subject_type])) {
                    $activity->subject_type = BaseOrder::class;
                }

                return $activity;
            })
            ->toArray();

        return [$result, $total];
    }
}