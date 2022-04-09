<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use Filterable;
    use SoftDeletes;

    protected $fillable = [
        'manufacturer_id',
        'source_id',
        'seller_id',
        'number',
        'number_external',
        'status',
        'product_code',
        'accepted_date',
        'order_date',
        'order_time',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const STATUS_ACCEPTED = 1;
    public const STATUS_TAKEN = 2;
    public const STATUS_SOLD = 3;
    public const STATUS_CANCELED = 4;

    public static function statusCaptions(): array
    {
        return [
            self::STATUS_ACCEPTED => __('order.statuses.accepted'),
            self::STATUS_TAKEN => __('order.statuses.taken'),
            self::STATUS_SOLD => __('order.statuses.sold'),
            self::STATUS_CANCELED => __('order.statuses.canceled'),
        ];
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

    public function details(): HasMany
    {
        return $this->hasMany(OrderDetail::class, 'order_id', 'id');
    }

    public function files(): BelongsToMany
    {
        return $this->belongsToMany(File::class, 'order_files');
    }
}
