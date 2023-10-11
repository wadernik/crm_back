<?php

declare(strict_types=1);

namespace App\Repositories\Seller;

use App\Models\Seller\Seller;
use App\Repositories\AbstractRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

final class SellerRepository extends AbstractRepository implements SellerRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(Seller::class);
    }

    public function addExtraFilter(Builder $builder, array &$criteria): void
    {
        if (isset($criteria['filter']['uuid'])) {
            $builder->where('uuid', $criteria['filter']['uuids']);
        }

        if (isset($criteria['filter']['uuids'])) {
            $builder->whereIn('uuid', $criteria['filter']['uuids']);
        }

        if (isset($criteria['filter']['uuid_not_null'])) {
            $builder->whereNotNull('uuid');
        }

        unset($criteria['filter']['uuid'], $criteria['filter']['uuids'], $criteria['filter']['uuid_not_null']);
    }

    public function find(int $id): ?Model
    {
        return Seller::query()->find($id);
    }
}