<?php

declare(strict_types=1);

namespace App\Managers\Board\Task;

use App\Models\Board\Task;

interface TaskDeleterInterface
{
    public function delete(Task $task): Task;
}