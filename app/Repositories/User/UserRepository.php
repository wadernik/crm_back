<?php

declare(strict_types=1);

namespace App\Repositories\User;

use App\Models\User\User;
use App\Repositories\AbstractRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

final class UserRepository extends AbstractRepository implements UserRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(User::query());
    }

    public function find(int $id): ?Model
    {
        return User::query()->find($id);
    }

    public function findByIds(int ...$ids): Collection
    {
        return User::query()
            ->where('id', $ids)
            ->get();
    }
}