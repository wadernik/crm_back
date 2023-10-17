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

    public function update(int $id, UpdateGroupDTOInterface $groupDTO): ?Group
    {
        /** @var Group $group */
        if (!$group = Group::query()->find($id)) {
            return null;
        }

        $group->update($groupDTO->toArray());

        return $group;
    }

    public function delete(int $id): ?Group
    {
        /** @var Group $group */
        if (!$group = Group::query()->find($id)) {
            return null;
        }

        $group->delete();

        return $group;
    }
}