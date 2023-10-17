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

    public function update(int $id, UpdateTaskDTOInterface $taskDTO): ?Task
    {
        /** @var Task $task */
        if (!$task = Task::query()->find($id)) {
            return null;
        }

        $task->update($taskDTO->toArray());

        return $task;
    }

    public function delete(int $id): ?Task
    {
        /** @var Task $task */
        if (!$task = Task::query()->find($id)) {
            return null;
        }

        $task->delete();

        return $task;
    }
}