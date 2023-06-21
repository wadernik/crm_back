<?php

declare(strict_types=1);

namespace App\Repositories\Activity;

use App\Models\Activity\ActivityExtended;
use App\Models\Order\Detail\OrderDetail;
use App\Repositories\AbstractRepository;
use Illuminate\Database\Eloquent\Builder;

final class ActivityRepository extends AbstractRepository implements ActivityRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(ActivityExtended::class);
    }

    // TODO: maybe redo this
    public function addExtraFilter(Builder $builder, array &$criteria): void
    {
        if (isset($criteria['filter']['subject'])) {
            $subject = array_filter(
                ActivityExtended::getSubjectsList(),
                fn(array $subject): bool => $subject['id'] === (int) $criteria['filter']['subject']
            );

            $criteria['filter']['subject_type'] = (array_shift($subject))['name'];
        }

        if (isset($criteria['filter']['date_start'])) {
            $builder->where('created_at', ">=", $criteria['filter']['date_start']);
        }

        if (isset($criteria['filter']['date_end'])) {
            $builder->where('created_at', "<=", $criteria['filter']['date_end']);
        }

        if (isset($criteria['filter']['detail'])) {
            $builder
                ->where(function (Builder $query) {
                    $query
                        ->where('subject_type', '<>', OrderDetail::class)
                        ->where('event', 'created');
                })
                ->orWhere('event', 'updated');
        }

        unset(
            $criteria['filter']['subject'],
            $criteria['filter']['date_start'],
            $criteria['filter']['date_end'],
            $criteria['filter']['detail']
        );
    }
}