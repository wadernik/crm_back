<?php

declare(strict_types=1);

namespace App\Repositories\Comment;

use App\Repositories\Sub\ApplyWithTrashedInterface;
use App\Repositories\Sub\CountInterface;
use App\Repositories\Sub\FindAllByCriteriaInterface;

interface CommentRepositoryInterface extends FindAllByCriteriaInterface,
                                             FindOneByIdInterface,
                                             ApplyWithTrashedInterface,
                                             CountInterface
{
}