<?php

declare(strict_types=1);

namespace App\Models\Order;

use App\Models\Order\Item\OrderItem;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property int                   id
 * @property int                   $manufacturer_id
 * @property int                   $source_id
 * @property int                   $seller_id
 * @property int                   $user_id
 * @property int                   inspector_id
 * @property string                $phone
 * @property string                $number
 * @property string                $number_external
 * @property int                   price
 * @property int                   $status
 * @property string                $order_date
 * @property string                $created_at
 * @property bool                  $draft
 * @property Collection<OrderItem> $items
 */
interface OrderInterface
{
    public function createdAt(): Attribute;

    public function updatedAt(): Attribute;

    public function price(): Attribute;

    public function seller(): BelongsTo;

    public function source(): BelongsTo;

    public function user(): BelongsTo;

    public function manufacturer(): BelongsTo;

    public function inspector(): BelongsTo;

    public function items(): HasMany;
}