<?php

declare(strict_types=1);

namespace App\Repositories\Dictionary;

use App\Models\Dictionary\Dictionary;
use App\Repositories\AbstractRepository;
use Illuminate\Database\Eloquent\Builder;

final class DictionaryRepository extends AbstractRepository implements DictionaryRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(Dictionary::class);
    }

    public function addExtraFilter(Builder $builder, array &$criteria): void
    {
        if (isset($criteria['filter']['uuids'])) {
            $builder->whereIn('uuid', $criteria['filter']['uuids']);
        }

        if (isset($criteria['filter']['value'])) {
            $builder->where('value', 'like', "%{$criteria['filter']['value']}%");
        }

        unset($criteria['filter']['uuids'], $criteria['filter']['value']);
    }
}