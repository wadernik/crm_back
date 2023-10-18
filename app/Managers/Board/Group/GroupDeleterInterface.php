<?php

declare(strict_types=1);

namespace App\Managers\Board\Group;

use App\Models\Board\Group;

interface GroupDeleterInterface
{
    public function delete(Group $group): Group;
}