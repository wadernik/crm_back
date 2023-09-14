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
    }
}