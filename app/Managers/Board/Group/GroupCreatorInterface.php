<?php

declare(strict_types=1);

namespace App\Managers\Board\Group;

use App\DTOs\Board\Group\CreateGroupDTOInterface;
use App\Models\Board\Group;

interface GroupCreatorInterface
{
    public function create(CreateGroupDTOInterface $groupDTO): Group;
}