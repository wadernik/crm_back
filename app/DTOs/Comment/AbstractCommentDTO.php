<?php

declare(strict_types=1);

namespace App\DTOs\Comment;

abstract class AbstractCommentDTO implements CreateCommentDTOInterface
{
    public function __construct(private readonly string $comment)
    {
    }

    public function comment(): string
    {
        return $this->comment;
    }
}