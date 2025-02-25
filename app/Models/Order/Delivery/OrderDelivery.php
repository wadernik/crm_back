<?php

declare(strict_types=1);

namespace App\Models\Order\Delivery;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        'delivery_date',
        'client_phone',
        'delivered_at',
    ];

    protected $appends = [
        'delivered',
    ];

    protected $hidden = [
        'order_id',
    ];

    protected $casts = [
        'delivered_at' => 'datetime:Y-m-d',
    ];

    protected static array $recordEvents = ['created', 'updated'];

    protected function delivered(): Attribute
    {
        return new Attribute(
            get: fn() => (bool) $this->delivered_at
        );
    }

    public function courier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'courier_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logExcept(['created_at', 'updated_at'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}