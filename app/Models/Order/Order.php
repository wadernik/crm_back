<?php

declare(strict_types=1);

namespace App\Models\Order;

use App\Models\Manufacturer\Manufacturer;
use App\Models\Order\Item\OrderItem;
use App\Models\Seller\Seller;
use App\Models\Traits\FilterableTrait;
use App\Models\Traits\HasComments;
use App\Models\Traits\SortableTrait;
use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Order extends Model implements OrderInterface
{
    use FilterableTrait;
    use HasComments;
    use LogsActivity;
    use SoftDeletes;
    use SortableTrait;

    protected $table = 'orders';

    protected $fillable = [
        'manufacturer_id',
        'source_id',
        'seller_id',
        'user_id',
        'inspector_id',
        'phone',
        'draft',
        'number',
        'number_external',
        'price',
        'status',
        'accepted_date',
        'order_date',
        'order_time',
        'updated_at',
    ];

    protected $hidden = [
        'draft',
    ];

    protected $appends = [
        'price_original',
    ];

    protected $casts = [
        'order_time' => 'datetime:H:i',
    ];

    protected static array $recordEvents = ['created', 'updated', 'deleted'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logExcept(['created_at', 'updated_at'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function createdAt(): Attribute
    {
        return new Attribute(
            get: function ($value) {
                return Carbon::parse($value)
                    ->setTimezone(config('app.timezone'))
                    ->format('Y-m-d H:i');
            }
        );
    }

    public function updatedAt(): Attribute
    {
        return new Attribute(
            get: function ($value) {
                return Carbon::parse($value)
                    ->setTimezone(config('app.timezone'))
                    ->format('Y-m-d H:i');
            }
        );
    }

    public function price(): Attribute
    {
        return new Attribute(
            get: fn ($value) => number_format((float) $value / 100, 2),
        );
    }

    protected function priceOriginal(): Attribute
    {
        return new Attribute(
            get: function () {
                if (!$this->price) {
                    return null;
                }

                return $this->getRawOriginal('price');
            }
        );
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class, 'seller_id');
    }

    public function source(): BelongsTo
    {
        return $this->belongsTo(Seller::class, 'source_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function manufacturer(): BelongsTo
    {
        return $this->belongsTo(Manufacturer::class, 'manufacturer_id');
    }

    public function inspector(): BelongsTo
    {
        return $this->belongsTo(User::class, 'inspector_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }
}