<?php

declare(strict_types=1);

namespace App\Services\Activity;

use App\Repositories\Activity\ActivityRepositoryInterface;

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

        $attributes = ['id', 'event', 'causer_id', 'subject_type', 'properties', 'created_at'];

        $sort = ['sort' => $requestParams['sort'] ?? 'created_at', 'order' => $requestParams['order'] ?? 'desc'];
        $limit = $requestParams['limit'] ?? null;
        $offset = $requestParams['page'] ?? null;

        $activities = ($this->repository->findAllBy($requestParams, $attributes, $sort, $limit, $offset));

        $total = $this->repository->count($requestParams);

        return [$activities->toArray(), $total];
    }
}