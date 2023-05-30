<?php

declare(strict_types=1);

namespace App\DTOs\Comment;

interface CreateCommentDTOInterface
{
    public function comment(): string;
}