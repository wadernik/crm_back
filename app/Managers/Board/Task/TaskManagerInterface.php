<?php

declare(strict_types=1);

namespace App\Managers\Board\Task;

interface TaskManagerInterface extends TaskCreatorInterface, TaskUpdaterInterface, TaskDeleterInterface
{
}