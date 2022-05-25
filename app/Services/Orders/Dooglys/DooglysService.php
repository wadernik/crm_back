<?php

namespace App\Services\Orders\Dooglys;

use Carbon\Carbon;

class DooglysService
{

    /**
     * @param DooglysApiClient $dooglysApiClient
     */
    public function __construct(private DooglysApiClient $dooglysApiClient)
    {
        $this->dooglysApiClient->init(
            config('dooglys.api_url'),
            config('dooglys.access_token')
        );
    }

    /**
     * @param string $dateCreatedAt
     * @param string $orderNumber
     * @return int
     */
    public function getOrderFinalPriceByNumber(string $dateCreatedAt, string $orderNumber): int
    {
        $dateStartTimestamp = Carbon::parse($dateCreatedAt)->startOfDay()->timestamp;
        $dateEndTimestamp = Carbon::parse($dateCreatedAt)->endOfDay()->timestamp;

        [$success, $orders] = $this->dooglysApiClient->listOrders($dateStartTimestamp, $dateEndTimestamp, $orderNumber);

        if (!$success) {
            return 0;
        }

        $order = collect($orders)->first(function (array $order) use ($orderNumber) {
            return $order['number'] === $orderNumber;
        });

        return $order['total_cost'] * 100 ?? 0;
    }
}
