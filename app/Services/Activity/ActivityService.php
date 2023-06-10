<?php

declare(strict_types=1);

namespace App\Services\Activity;

use App\Models\Comment\CustomComments;
use App\Models\Order\BaseOrder;
use App\Models\Order\Detail\OrderDetail;
use App\Models\Order\File\OrderFile;
use App\Repositories\Activity\ActivityRepositoryInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

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

        $attributes = ['id', 'event', 'causer_id', 'subject_type', 'properties', 'updated_at'];

        $sort = ['sort' => $requestData['sort'] ?? 'id', 'order' => $requestData['order'] ?? 'desc'];
        $limit = $requestData['limit'] ?? null;
        $offset = $requestData['page'] ?? null;

        $activities = ($this->repository->findAllBy($requestParams, $attributes, $sort, $limit, $offset))->toArray();

        $total = $this->repository->count($requestParams);

        $result = collect($activities)
            ->mapToGroups(static function (array $activity) {
                $groupBy = $activity['subject_type'];

                if (isset(self::WHITELISTED_CLASSES[$groupBy])) {
                    $groupBy = BaseOrder::class;
                }

                return [$groupBy => $activity];
            })
            ->map(static function (Collection $activitiesGroup) {
                $activitiesGroup = $activitiesGroup->sortByDesc(static function ($activity) {
                    return $activity['id'];
                });

                return $activitiesGroup->sortByDesc(static function ($activity) {
                    return Carbon::parse($activity['updated_at'])->timestamp;
                });
            })
            ->toArray();

        return [$result, $total];
    }
}