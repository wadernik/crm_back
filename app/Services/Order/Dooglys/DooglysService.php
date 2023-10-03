<?php

declare(strict_types=1);

namespace App\Services\Order\Dooglys;

use Closure;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Carbon;
use function app;
use function collect;
use function config;

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

        $order = collect($response->orders())->first($this->lambda($number));

        return ($order['total_cost'] ?? 0) * 100;
    }

    private function lambda(string $number): Closure
    {
        return static function (array $order) use ($number): bool {
            return $order['number'] === $number;
        };
    }
}