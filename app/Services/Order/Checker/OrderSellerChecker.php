<?php

declare(strict_types=1);

namespace App\Services\Order\Checker;

use App\Repositories\Seller\SellerRepositoryInterface;

final class OrderSellerChecker implements OrderSellerCheckerInterface
{
    public function __construct(private readonly SellerRepositoryInterface $sellerRepository)
    {
    }

    public function check(int $sellerId): bool
    {
        $seller = $this->sellerRepository->find($sellerId);

        return (bool) $seller?->as_pickup_point;
    }
}