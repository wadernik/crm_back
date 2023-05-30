<?php

declare(strict_types=1);

namespace App\Repositories\Permission;

use App\Models\Permission\Permission;
use App\Repositories\AbstractRepository;

final class PermissionRepository extends AbstractRepository implements PermissionRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(Permission::query());
    }
}