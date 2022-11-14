<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class OrderDetail extends Model
{
    use SoftDeletes;
    use LogsActivity;

    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'name',
        'amount',
        'label',
        'comment',
        'decoration',
    ];

    protected $hidden = [
        'id',
        'order_id',
        'deleted_at',
    ];

    protected static array $recordEvents = ['created', 'updated'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
