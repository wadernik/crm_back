<?php

declare(strict_types=1);

namespace App\Repositories\ManufacturerDateLimit;

use App\Models\Manufacturer\ManufacturerDateLimit;
use App\Repositories\AbstractRepository;
use Illuminate\Database\Eloquent\Builder;
use function collect;

final class DateLimitRepository extends AbstractRepository implements DateLimitRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(ManufacturerDateLimit::class);
    }

    public function find(int $id): ?ManufacturerDateLimit
    {
        /** @var ManufacturerDateLimit $dateLimit */
        $dateLimit = ManufacturerDateLimit::query()->find($id);

        return $dateLimit;
    }

    public function addExtraFilter(Builder $builder, array &$criteria): void
    {
        if (isset($criteria['filter']['date_gte'])) {
            $builder->where('date', '>=', $criteria['filter']['date_gte']);
            unset($criteria['filter']['date_gte']);
        }

        if (isset($criteria['filter']['date_lte'])) {
            $builder->where('date', '<=', $criteria['filter']['date_lte']);
            unset($criteria['filter']['date_lte']);
        }
    }

    public function limitTypes(): array
    {
        return collect(ManufacturerDateLimit::limitTypes())
            ->map(function (string $statusCaption, int $status) {
                return [
                    'id' => $status,
                    'name' => $statusCaption,
                ];
            })
            ->values()
            ->toArray();
    }
}