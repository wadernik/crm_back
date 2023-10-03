<?php

declare(strict_types=1);

namespace App\Services\Order\Status;

use App\Models\Order\OrderStatus;
use function collect;

final class OrderStatusesRetriever implements OrderStatusesRetrieverInterface
{
    public function get(): array
    {
        return collect(OrderStatus::statusCaptions())
            ->map(function (string $statusCaption, int $status) {
                return [
                    'id' => $status,
                    'name' => $statusCaption,
                ];
            })
            ->values()
            ->toArray();
    }
}