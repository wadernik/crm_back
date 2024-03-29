<?php

declare(strict_types=1);

namespace App\Repositories\Manufacturer;

use App\Models\Manufacturer\Manufacturer;
use App\Repositories\AbstractRepository;
use Illuminate\Database\Eloquent\Builder;

final class ManufacturerRepository extends AbstractRepository implements ManufacturerRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(Manufacturer::class);
    }

    public function addExtraFilter(Builder $builder, array &$criteria): void
    {
    }

    public function find(int $id): ?Manufacturer
    {
        /** @var Manufacturer $manufacturer */
        $manufacturer = Manufacturer::query()->find($id);

        return $manufacturer;
    }
}