<?php

declare(strict_types=1);

namespace App\Managers\Seller;

use App\DTOs\Seller\CreateSellerDTOInterface;
use App\DTOs\Seller\UpdateSellerDTOInterface;
use App\Models\Seller\Seller;
use Illuminate\Database\Eloquent\Model;

final class SellerManager implements SellerManagerInterface
{
    public function create(CreateSellerDTOInterface $sellerDTO): Model
    {
        return Seller::query()->create($sellerDTO->toArray());
    }

    public function update(int $id, UpdateSellerDTOInterface $sellerDTO): ?Model
    {
        if (!$seller = Seller::query()->find($id)) {
            return null;
        }

        $seller->update($sellerDTO->toArray());

        return $seller;
    }

    public function delete(int $id): ?Model
    {
        if (!$seller = Seller::query()->find($id)) {
            return null;
        }

        $seller->delete();

        return $seller;
    }
}