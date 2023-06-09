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

    public function addExtraFilter(Builder $builder, array &$criteria): void
    {
    }
}