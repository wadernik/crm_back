<?php

declare(strict_types=1);

namespace App\Repositories\Board\Task;

use App\Models\Board\Task;
use App\Repositories\AbstractRepository;
use Illuminate\Database\Eloquent\Builder;

final class TaskRepository extends AbstractRepository implements TaskRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(Task::class);
    }

    public function addExtraFilter(Builder $builder, array &$criteria): void
    {
    }

    public function find(int $id): ?Task
    {
        /** @var Task $task */
        $task = Task::query()->find($id);

        return $task;
    }
}