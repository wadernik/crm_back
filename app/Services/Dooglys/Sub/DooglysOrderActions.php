<?php

declare(strict_types=1);

namespace App\Services\Dooglys\Sub;

final class DooglysOrderActions extends AbstractDooglysAction implements DooglysOrderActionsInterface
{
    public function orders(int|string $dateStart, string $orderNumber): array
    {
        $requestParams = [
            'query' => [
                'date_created_from' => $dateStart,
                'per-page' => 15,
                'page' => 1,
                'term' => $orderNumber
            ]
        ];

        return $this->apiClient
            ->request('/sales/order/list')
            ->method('get')
            ->params($requestParams)
            ->execute()
            ->data();
    }
}