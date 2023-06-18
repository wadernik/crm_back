<?php

declare(strict_types=1);

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int id
 * @property int $manufacturer_id
 * @property int $source_id
 * @property int $seller_id
 * @property int $user_id
 * @property string $number
 * @property string $number_external
 * @property int price
 * @property int $status
 * @property string $product_code
 * @property string $order_date
 * @property string $created_at
 */
interface OrderInterface
{
    /**
     * @return array<string, string>
     */
    public static function statusCaptions(): array;

    public function createdAt(): Attribute;

    public function updatedAt(): Attribute;

    public function price(): Attribute;

    public function seller(): BelongsTo;

    public function source(): BelongsTo;

    public function manufacturer(): BelongsTo;

    public function details(): HasOne;

    public function files(): BelongsToMany;
}