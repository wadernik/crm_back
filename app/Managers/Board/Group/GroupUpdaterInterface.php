<?php

declare(strict_types=1);

namespace App\Managers\Board\Group;

use App\DTOs\Board\Group\UpdateGroupDTOInterface;
use App\Models\Board\Group;

interface GroupUpdaterInterface
{
    public function update(Group $group, UpdateGroupDTOInterface $groupDTO): Group;
}