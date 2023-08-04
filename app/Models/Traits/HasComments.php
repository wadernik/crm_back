<?php

namespace App\Models\Traits;

use App\Models\Comment\Comment;
use App\Models\Contracts\CommentatorInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasComments
{
    /**
     * Return all comments for this model.
     *
     * @return MorphMany
     */
    public function comments(): MorphMany
    {
        return $this->morphMany(config('comments.comment_class'), 'commentable');
    }

    /**
     * Attach a comment to this model.
     *
     * @param string $comment
     *
     * @return Model
     */
    public function comment(string $comment): Model
    {
        return $this->commentAsUser(auth()->user(), $comment);
    }

    /**
     * Attach a comment to this model as a specific user.
     *
     * @param Model|null $user
     * @param string $comment
     *
     * @return Model
     */
    public function commentAsUser(?Model $user, string $comment): Model
    {
        /** @var string $commentClass */
        $commentClass = config('comments.comment_class', Comment::class);

        $comment = new $commentClass([
            'comment' => $comment,
            'is_approved' => $user instanceof CommentatorInterface && !$user->needsCommentApproval($this),
            'user_id' => is_null($user) ? null : $user->getKey(),
            'commentable_id' => $this->getKey(),
            'commentable_type' => __CLASS__,
        ]);

        return $this->comments()->save($comment);
    }

}