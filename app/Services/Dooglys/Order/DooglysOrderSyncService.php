<?php

declare(strict_types=1);

namespace App\Services\Dooglys\Order;

use App\Services\Dooglys\Sub\DooglysOrderActionsInterface;
use Illuminate\Support\Carbon;
use function App\Helpers\Functions\load_service;
use function collect;

final class DooglysOrderSyncService implements DooglysOrderSyncServiceInterface
{
    private DooglysOrderActionsInterface $dooglysOrderActions;

    public function __construct()
    {
        $this->dooglysOrderActions = load_service(DooglysOrderActionsInterface::class);
    }

    public function finalPrice(string $date, string $orderNumber): int
    {
        $dateStartTimestamp = Carbon::parse($date)->startOfDay()->timestamp;

        $orders = $this->dooglysOrderActions->orders($dateStartTimestamp, $orderNumber);

        if (!$orders) {
            return 0;
        }

        $order = collect($orders)->first(static function (array $order) use ($orderNumber): bool {
            return $order['number'] === $orderNumber;
        });

        return ($order['total_cost'] ?? 0) * 100;
    }
}