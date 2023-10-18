<?php

declare(strict_types=1);

namespace App\DTOs\Board\Board;

final class CreateBoardDTO implements CreateBoardDTOInterface
{
    /**
     * @param array{
     *     name: string,
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