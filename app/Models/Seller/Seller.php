<?php

namespace App\Models\Seller;

use App\Models\Traits\FilterableTrait;
use App\Models\Traits\SortableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Seller extends Model implements SellerInterface
{
    use FilterableTrait;
    use SortableTrait;
    use SoftDeletes;
    use LogsActivity;

    protected $fillable = [
        'name',
        'address',
        'phone',
        'email',
        'working_hours',
        'latitude',
        'longitude',
        'uuid',
        'menu_id',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logExcept(['created_at', 'updated_at'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}