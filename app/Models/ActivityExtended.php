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

    public static function getSubjectsList(): array
    {
        return [
            [
                'name' => BaseOrder::class,
                'display_name' => __('activities.display_name.base_order'),
            ],
            [
                'name' => Manufacturer::class,
                'display_name' => __('activities.display_name.manufacturer'),
            ],
            [
                'name' => ManufacturerDateLimit::class,
                'display_name' => __('activities.display_name.manufacturer_date_limit'),
            ],
            [
                'name' => Permission::class,
                'display_name' => __('activities.display_name.permission'),
            ],
            [
                'name' => Role::class,
                'display_name' => __('activities.display_name.role'),
            ],
            [
                'name' => Seller::class,
                'display_name' => __('activities.display_name.seller'),
            ],
            [
                'name' => User::class,
                'display_name' => __('activities.display_name.user'),
            ],
        ];
    }
}