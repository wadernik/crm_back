<?php

declare(strict_types=1);

namespace App\Repositories\ManufacturerDateLimit;

use App\Models\Manufacturer\ManufacturerDateLimit;
use App\Repositories\AbstractRepository;
use Illuminate\Database\Eloquent\Model;

final class DateLimitRepository extends AbstractRepository implements DateLimitRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(ManufacturerDateLimit::query());
    }

    public function find(int $id): ?Model
    {
        return ManufacturerDateLimit::query()->find($id);
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