<?php

declare(strict_types=1);

namespace App\Repositories\Order\File;

use App\Models\Order\File\OrderFile;
use Illuminate\Database\Eloquent\Collection;

final class OrderFileRepository implements OrderFileRepositoryInterface
{
    public function aggregateByOrderItemIds(array $itemIds): Collection
    {
        return OrderFile::query()
            ->selectRaw('order_item_id, count(*) as amount')
            ->whereIn('order_item_id', $itemIds)
            ->groupBy('order_item_id')
            ->get()
            ->keyBy('order_item_id');
    }
}