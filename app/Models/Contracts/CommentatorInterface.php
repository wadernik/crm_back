<?php

namespace App\Models\Contracts;

interface CommentatorInterface
{
    /**
     * Check if a comment for a specific model needs to be approved.
     * @param mixed $model
     * @return bool
     */
    public function needsCommentApproval(mixed $model): bool;
}