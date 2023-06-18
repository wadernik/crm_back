<?php

declare(strict_types=1);

namespace App\Repositories\Permission;

use App\Models\Permission\Permission;
use App\Repositories\AbstractRepository;
use Illuminate\Database\Eloquent\Builder;

final class PermissionRepository extends AbstractRepository implements PermissionRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(Permission::class);
    }

    public function addExtraFilter(Builder $builder, array &$criteria): void
    {
    }
}