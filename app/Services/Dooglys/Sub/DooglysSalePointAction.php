<?php

declare(strict_types=1);

namespace App\Services\Dooglys\Sub;

final class DooglysSalePointAction extends AbstractDooglysAction implements DooglysSalePointActionInterface
{
    public function salePoints(): array
    {
        return $this->apiClient
            ->request('/structure/sale-point/list')
            ->method('get')
            ->execute()
            ->data();
    }
}