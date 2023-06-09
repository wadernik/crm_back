<?php

declare(strict_types=1);

namespace App\Repositories\Manufacturer;

use App\Models\Manufacturer\Manufacturer;
use App\Repositories\AbstractRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

final class ManufacturerRepository extends AbstractRepository implements ManufacturerRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(Manufacturer::query());
    }

    public function addExtraFilter(Builder $builder, array &$criteria): void
    {
    }

    public function find(int $id): ?Model
    {
        return Manufacturer::query()->find($id);
    }
}