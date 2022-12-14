<?php

namespace App\Models;

use App\Models\Traits\FilterableTrait;
use App\Models\Traits\SortableTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use JetBrains\PhpStorm\ArrayShape;
use Parental\HasChildren;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class BaseOrder extends Model
{
    use FilterableTrait;
    use SoftDeletes;
    use SortableTrait;
    use HasChildren;
    use LogsActivity;

    protected $table = 'orders';

    protected $fillable = [
        'manufacturer_id',
        'source_id',
        'seller_id',
        'user_id',
        'number',
        'number_external',
        'price',
        'status',
        'product_code',
        'accepted_date',
        'order_date',
        'order_time',
        'type',
        'updated_at',
    ];

    protected $hidden = [
        'parental_type',
        'deleted_at',
        'type',
    ];

    protected $appends = [
        'price_original',
    ];

    protected $casts = [
        'order_time' => 'datetime:H:i',
    ];

    protected static array $recordEvents = ['created', 'updated', 'deleted'];

    public const STATUS_ACCEPTED = 1;
    public const STATUS_TAKEN = 2; // Взят на исполнение
    public const STATUS_DELIVERY = 3;
    public const STATUS_SOLD = 4;
    public const STATUS_CANCELED = 5;

    #[ArrayShape([
        self::STATUS_ACCEPTED => "mixed",
        self::STATUS_TAKEN => "mixed",
        self::STATUS_DELIVERY => "mixed",
        self::STATUS_SOLD => "mixed",
        self::STATUS_CANCELED => "mixed"
    ])] public static function statusCaptions(): array
    {
        return [
            self::STATUS_ACCEPTED => __('order.statuses.accepted'),
            self::STATUS_TAKEN => __('order.statuses.taken'),
            self::STATUS_DELIVERY => __('order.statuses.delivery'),
            self::STATUS_SOLD => __('order.statuses.sold'),
            self::STATUS_CANCELED => __('order.statuses.canceled'),
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logExcept(['updated_at'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /**
     * @return Attribute
     */
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

    /**
     * @return Attribute
     */
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

    /**
     * @return Attribute
     */
    public function price(): Attribute
    {
        return new Attribute(
            get: fn ($value) => number_format((float) $value / 100, 2),
        );
    }

    /**
     * @return Attribute
     */
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

    public function manufacturer(): BelongsTo
    {
        return $this->belongsTo(Manufacturer::class, 'manufacturer_id');
    }

    public function details(): HasOne
    {
        return $this->hasOne(OrderDetail::class, 'order_id', 'id');
    }

    public function files(): BelongsToMany
    {
        return $this->belongsToMany(File::class, 'order_files', 'order_id', 'file_id')->using(OrderFile::class);
    }
}
