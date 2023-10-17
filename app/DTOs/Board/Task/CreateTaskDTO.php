<?php

declare(strict_types=1);

namespace App\DTOs\Board\Task;

final class CreateTaskDTO implements CreateTaskDTOInterface
{
    /**
     * @param array{
     *     group_id: int,
     *     sort: int,
     *     name: string,
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