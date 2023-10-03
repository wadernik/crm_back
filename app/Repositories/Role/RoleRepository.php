<?php

declare(strict_types=1);

namespace App\Repositories\Role;

use App\Models\Role\Role;
use App\Repositories\AbstractRepository;
use Illuminate\Database\Eloquent\Builder;

final class RoleRepository extends AbstractRepository implements RoleRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(Role::class);
    }

    public function addExtraFilter(Builder $builder, array &$criteria): void
    {
    }
}