<?php

declare(strict_types=1);

namespace App\Repositories\Order\File;

use Illuminate\Database\Eloquent\Collection;

interface AggregateTotalFilesInterface
{
    /**
     * @param array<int> $itemIds
     *
     * @return Collection<array{order_item_id: int, amount: int}>
     */
    public function aggregateByOrderItemIds(array $itemIds): Collection;
}