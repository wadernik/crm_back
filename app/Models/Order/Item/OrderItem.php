<?php

namespace App\Models\Order\Item;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class OrderItem extends Model
{
    use SoftDeletes;
    use LogsActivity;

    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'title_id',
        'name',
        'amount',
        'label',
        'comment',
        'decoration',
    ];

    protected $hidden = [
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