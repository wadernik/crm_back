<?php

declare(strict_types=1);

namespace App\Managers\Seller;

use App\DTOs\Seller\CreateSellerDTOInterface;
use App\DTOs\Seller\UpdateSellerDTOInterface;
use App\Models\Seller\Seller;

final class SellerManager implements SellerManagerInterface
{
    public function create(CreateSellerDTOInterface $sellerDTO): Seller
    {
        /** @var Seller $seller */
        $seller = Seller::query()->create($sellerDTO->toArray());

        return $seller;
    }

    public function update(Seller $seller, UpdateSellerDTOInterface $sellerDTO): Seller
    {
        $seller->update($sellerDTO->toArray());

        return $seller;
    }

    public function delete(Seller $seller): Seller
    {
        $seller->delete();

        return $seller;
    }
}