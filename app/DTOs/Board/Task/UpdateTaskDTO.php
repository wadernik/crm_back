<?php

declare(strict_types=1);

namespace App\DTOs\Board\Task;

use App\DTOs\Board\Task\UpdateTaskDTOInterface;

final class UpdateTaskDTO implements UpdateTaskDTOInterface
{
    /**
     * @param array{
     *     group_id: int|null,
     *     sort: int|null,
     *     name: string|null,
     *     description: string|null,
     *     date_from: string|null,
     *     date_to: string|null,
     *     time_to: string|null
     * } $attributes
     */
    public function __construct(private readonly array $attributes)
    {
    }

    public function toArray(): array
    {
        return $this->attributes;
    }
}