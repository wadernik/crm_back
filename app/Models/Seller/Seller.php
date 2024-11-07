<?php

namespace App\Models\Seller;

use App\Models\Traits\FilterableTrait;
use App\Models\Traits\SortableTrait;
use Illuminate\Database\Eloquent\Casts\Attribute;
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
        'short_address',
        'phone',
        'email',
        'working_hours',
        'latitude',
        'longitude',
        'uuid',
        'menu_id',
        'as_pickup_point',
        'created_by',
    ];

    protected $hidden = [
        'short_address',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logExcept(['created_at', 'updated_at'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function address(): Attribute
    {
        return new Attribute(
            get: function () {
                return $this->short_address;
            }
        );
    }
}
