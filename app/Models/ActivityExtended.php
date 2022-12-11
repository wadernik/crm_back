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
                'id' => 1,
                'name' => BaseOrder::class,
                'display_name' => __('activities.display_name.base_order'),
            ],
            [
                'id' => 2,
                'name' => Manufacturer::class,
                'display_name' => __('activities.display_name.manufacturer'),
            ],
            [
                'id' => 3,
                'name' => ManufacturerDateLimit::class,
                'display_name' => __('activities.display_name.manufacturer_date_limit'),
            ],
            [
                'id' => 4,
                'name' => Permission::class,
                'display_name' => __('activities.display_name.permission'),
            ],
            [
                'id' => 5,
                'name' => Role::class,
                'display_name' => __('activities.display_name.role'),
            ],
            [
                'id' => 6,
                'name' => Seller::class,
                'display_name' => __('activities.display_name.seller'),
            ],
            [
                'id' => 7,
                'name' => User::class,
                'display_name' => __('activities.display_name.user'),
            ],
            [
                'id' => 8,
                'name' => OrderFile::class,
                'display_name' => __('activities.display_name.order_file'),
            ],
            [
                'id' => 9,
                'name' => CustomComments::class,
                'display_name' => __('activities.display_name.comments'),
            ]
        ];
    }
}