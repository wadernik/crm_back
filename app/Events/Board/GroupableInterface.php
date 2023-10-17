<?php

declare(strict_types=1);

namespace App\Events\Board;

use App\Models\Board\Group;

interface GroupableInterface
{
    public function group(): Group;
}