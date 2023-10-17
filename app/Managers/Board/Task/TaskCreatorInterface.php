<?php

declare(strict_types=1);

namespace App\Managers\Board\Task;

use App\DTOs\Board\Task\CreateTaskDTOInterface;
use App\Models\Board\Task;

interface TaskCreatorInterface
{
    public function create(CreateTaskDTOInterface $taskDTO): Task;
}