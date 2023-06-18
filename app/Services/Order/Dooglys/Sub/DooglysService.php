<?php

declare(strict_types=1);

namespace App\Services\Order\Dooglys\Sub;

use App\Services\Order\Dooglys\DooglysApiClientInterface;
use App\Services\Order\Dooglys\DooglysServiceInterface;
use Carbon\Carbon;
use Illuminate\Contracts\Container\BindingResolutionException;

final class DooglysService implements DooglysServiceInterface
{
    private DooglysApiClientInterface $apiClient;

    /**
     * @throws BindingResolutionException
     */
    public function __construct()
    {
        $this->apiClient = app()->make(
            DooglysApiClientInterface::class,
            ['api' => config('dooglys.api_url'), 'token' => config('dooglys.access_token')]
        );
    }

    public function finalPrice(string $date, string $number): int
    {
        $dateStartTimestamp = Carbon::parse($date)->startOfDay()->timestamp;
        $dateEndTimestamp = Carbon::parse($date)->endOfDay()->timestamp;

        $response = $this->apiClient->orders($dateStartTimestamp, $dateEndTimestamp, $number);

        if (!$response->status()) {
            return 0;
        }

        $order = collect($response->orders())->first(function (array $order) use ($number) {
            return $order['number'] === $number;
        });

        return ($order['total_cost'] ?? 0) * 100;
    }
}