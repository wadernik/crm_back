<?php

declare(strict_types=1);

namespace App\Repositories\Seller;

use App\Models\Seller\Seller;
use App\Repositories\AbstractRepository;
use Illuminate\Database\Eloquent\Model;

final class SellerRepository extends AbstractRepository implements SellerRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(Seller::query());
    }

    public function find(int $id): ?Model
    {
        return Seller::query()->find($id);
    }
}