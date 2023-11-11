<?php

declare(strict_types=1);

namespace App\Repositories\Permission\Section;

use App\Models\Permission\PermissionSection;
use App\Repositories\AbstractRepository;
use Illuminate\Database\Eloquent\Builder;

final class PermissionSectionRepository extends AbstractRepository implements PermissionSectionRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(PermissionSection::class);
    }

    public function addExtraFilter(Builder $builder, array &$criteria): void
    {
    }
}