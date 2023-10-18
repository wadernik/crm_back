<?php

declare(strict_types=1);

namespace App\DTOs\Board\Group;

final class CreateGroupDTO implements CreateGroupDTOInterface
{
    /**
     * @param array{
     *     board_id: int,
     *     name: string,
     *     sort: int
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