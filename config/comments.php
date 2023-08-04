<?php

use App\Models\Comment\Comment;
use App\Models\User\User;

return [

    /*
     * The comment class that should be used to store and retrieve
     * the comments.
     */
    'comment_class' => Comment::class,

    /*
     * The user model that should be used when associating comments with
     * commentators. If null, the default user provider from your
     * Laravel authentication configuration will be used.
     */
    'user_model' => User::class,
];