<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Traits\FilterableTrait;
use App\Models\Traits\SortableTrait;
use Spatie\Activitylog\Models\Activity;

final class ActivityExtended extends Activity
{
    use FilterableTrait;
    use SortableTrait;
}