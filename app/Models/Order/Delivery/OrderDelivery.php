<?php

declare(strict_types=1);

namespace App\Models\Order\Delivery;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class OrderDelivery extends Model implements OrderDeliveryInterface
{
    use SoftDeletes;
    use LogsActivity;

    protected $fillable = [
        'order_id',
        'courier_id',
        'address',
        'delivery_price',
    ];

    protected static array $recordEvents = ['created', 'updated'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logExcept(['created_at', 'updated_at'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}