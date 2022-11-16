<?php

namespace App\Models;

use App\Models\Traits\FilterableTrait;
use App\Models\Traits\SortableTrait;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Seller extends Model
{
    use FilterableTrait;
    use SortableTrait;
    use LogsActivity;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'address',
        'phone',
        'email',
        'working_hours',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
