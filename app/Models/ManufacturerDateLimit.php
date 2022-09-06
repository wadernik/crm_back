<?php

namespace App\Models;

use App\Models\Traits\FilterableTrait;
use App\Models\Traits\SortableTrait;
use Illuminate\Database\Eloquent\Model;

class ManufacturerDateLimit extends Model
{
    use FilterableTrait;
    use SortableTrait;

    public $timestamps = false;

    protected $fillable = [
        'manufacturer_id',
        'date',
        'limit_type',
    ];

    public const STATUS_FULL_STOP = 1;
    public const STATUS_PARTIAL_STOP = 2;

    public static function limitTypes(): array
    {
        return [
            self::STATUS_FULL_STOP => __('manufacturer_limits.statuses.full_stop'),
            self::STATUS_PARTIAL_STOP => __('manufacturer_limits.statuses.partial_stop'),
        ];
    }
}
