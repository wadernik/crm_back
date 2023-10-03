<?php

declare(strict_types=1);

namespace App\Repositories\Activity;

use App\Models\Activity\ActivityExtended;
use App\Models\Order\Item\OrderItem;
use App\Repositories\AbstractRepository;
use Illuminate\Database\Eloquent\Builder;
use function is_array;
use function array_filter;
use function array_shift;

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
                static fn(array $subject): bool => $subject['id'] === (int) $criteria['filter']['subject']
            );

            $builder->where('subject_type', (array_shift($subject))['name']);
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
                    $query->where(function (Builder $innerQuery) {
                        $innerQuery
                            ->where('subject_type', '<>', OrderItem::class)
                            ->where('event', 'created');
                    })
                    ->orWhere('event', 'updated');
                });
        }

        if (isset($criteria['filter']['subject_id'])) {
            is_array($criteria['filter']['subject_id'])
                ? $builder->whereIn('subject_id', $criteria['filter']['subject_id'])
                : $builder->where('subject_id', $criteria['filter']['subject_id']);
        }

        unset(
            $criteria['filter']['subject'],
            $criteria['filter']['subject_id'],
            $criteria['filter']['date_start'],
            $criteria['filter']['date_end'],
            $criteria['filter']['detail']
        );
    }
}