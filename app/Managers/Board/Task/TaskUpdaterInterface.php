<?php

declare(strict_types=1);

namespace App\Managers\Board\Task;

use App\DTOs\Board\Task\UpdateTaskDTOInterface;
use App\Models\Board\Task;

interface TaskUpdaterInterface
{
    public function update(int $id, UpdateTaskDTOInterface $taskDTO): ?Task;
}