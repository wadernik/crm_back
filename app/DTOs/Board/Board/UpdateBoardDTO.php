<?php

declare(strict_types=1);

namespace App\DTOs\Board\Board;

final class UpdateBoardDTO implements UpdateBoardDTOInterface
{
    /**
     * @param array{
     *     name: string|null,
     *     file_id: int|null
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