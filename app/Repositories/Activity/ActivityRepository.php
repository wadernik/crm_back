<?php

declare(strict_types=1);

namespace App\Repositories\Activity;

use App\Models\Activity\ActivityExtended;
use App\Repositories\AbstractRepository;
use Illuminate\Database\Eloquent\Builder;

final class ActivityRepository extends AbstractRepository implements ActivityRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(ActivityExtended::query());
    }

    // TODO: maybe redo this
    public function addExtraFilter(Builder $builder, array &$criteria): void
    {
        if (isset($criteria['filter']['subject'])) {
            $subject = array_filter(
                ActivityExtended::getSubjectsList(),
                fn(array $subject): bool => $subject['id'] === (int) $criteria['filter']['subject']
            );

            unset($criteria['filter']['subject']);

            $criteria['filter']['subject_type'] = (array_shift($subject))['name'];
        }
    }
}