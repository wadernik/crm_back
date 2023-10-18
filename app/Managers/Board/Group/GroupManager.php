<?php

declare(strict_types=1);

namespace App\Managers\Board\Group;

use App\DTOs\Board\Group\CreateGroupDTOInterface;
use App\DTOs\Board\Group\UpdateGroupDTOInterface;
use App\Models\Board\Group;

final class GroupManager implements GroupManagerInterface
{
    public function create(CreateGroupDTOInterface $groupDTO): Group
    {
        /** @var Group $group */
        $group = Group::query()->create($groupDTO->toArray());

        return $group;
    }

    public function update(Group $group, UpdateGroupDTOInterface $groupDTO): Group
    {
        $group->update($groupDTO->toArray());

        return $group;
    }

    public function delete(Group $group): Group
    {
        $group->delete();

        return $group;
    }
}