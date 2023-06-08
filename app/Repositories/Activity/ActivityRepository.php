<?php

declare(strict_types=1);

namespace App\Repositories\Activity;

use App\Models\Activity\ActivityExtended;
use App\Repositories\AbstractRepository;

final class ActivityRepository extends AbstractRepository implements ActivityRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(ActivityExtended::query());
    }
}