<?php

declare(strict_types=1);

namespace App\Services\Activity;

use App\Models\BaseOrder;
use App\Models\OrderDetail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

final class ActivitiesService implements ActivitiesInterface
{
    private ActivityCollectionService $activityCollectionService;

    public function __construct()
    {
        $this->activityCollectionService = app(ActivityCollectionService::class);
    }

    public function getActivities(?int $subjectId = null, array $requestParams = []): array
    {
        if ($subjectId) {
            $requestParams['filter']['subject_id'] = $subjectId;
        }

        $attributes = ['id', 'event', 'causer_id', 'subject_type', 'properties', 'updated_at'];

        $activities = $this->activityCollectionService->getInstances(
            attributes: $attributes,
            requestParams: $requestParams
        );

        $total = $this->activityCollectionService->countInstances($requestParams);

        $result = collect($activities)
            ->mapToGroups(static function (array $activity) {
                $groupBy = $activity['subject_type'];

                if ($groupBy === OrderDetail::class) {
                    $groupBy = BaseOrder::class;
                }

                return [$groupBy => $activity];
            })
            ->map(static function (Collection $activitiesGroup) {
                return $activitiesGroup->sortByDesc(static function ($activity) {
                    return Carbon::parse($activity['updated_at'])->timestamp;
                });
            })
            ->toArray();

        return [$result, $total];
    }
}
