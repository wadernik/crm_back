<?php

declare(strict_types=1);

namespace App\Services\Dooglys\Sub;

final class DooglysMenuAction extends AbstractDooglysAction implements DooglysMenuActionInterface
{
    public function menu(string $uuid): array
    {
        $requestParams = [
            'query' => [
                'id' => $uuid,
            ]
        ];

        return $this->apiClient
            ->request('/nomenclature/menu/view')
            ->method('get')
            ->params($requestParams)
            ->execute()
            ->data();
    }
}