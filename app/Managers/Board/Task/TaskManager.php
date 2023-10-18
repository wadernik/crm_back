<?php

declare(strict_types=1);

namespace App\Managers\Board\Task;

use App\DTOs\Board\Task\CreateTaskDTOInterface;
use App\DTOs\Board\Task\UpdateTaskDTOInterface;
use App\Models\Board\Task;

final class TaskManager implements TaskManagerInterface
{
    public function create(CreateTaskDTOInterface $taskDTO): Task
    {
        /** @var Task $task */
        $task = Task::query()->create($taskDTO->toArray());

        return $task;
    }

    public function update(Task $task, UpdateTaskDTOInterface $taskDTO): Task
    {
        $task->update($taskDTO->toArray());

        return $task;
    }

    public function delete(Task $task): Task
    {
        $task->delete();

        return $task;
    }
}