<?php

declare(strict_types=1);

namespace App\Services\Activity;

use App\ModelModifiers\ModelFilters\ActivitiesFilter;
use App\ModelModifiers\ModelSorts\ActivitiesSort;
use App\Models\ActivityExtended;
use App\Services\AbstractBaseCollectionService;

final class ActivityCollectionService extends AbstractBaseCollectionService
{
    public function __construct(ActivityExtended $activity, ActivitiesFilter $filter, ActivitiesSort $sort)
    {
        $this->modelClass = $activity;
        $this->modelFilter = $filter;
        $this->modelSort = $sort;
    }
}