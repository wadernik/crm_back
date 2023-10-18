<?php

declare(strict_types=1);

namespace App\Repositories\Board\Group;

use App\Models\Board\Group;
use App\Repositories\AbstractRepository;
use Illuminate\Database\Eloquent\Builder;

final class GroupRepository extends AbstractRepository implements GroupRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(Group::class);
    }

    public function addExtraFilter(Builder $builder, array &$criteria): void
    {
    }

    public function find(int $id): ?Group
    {
        /** @var Group $group */
        $group = Group::query()->find($id);

        return $group;
    }
}