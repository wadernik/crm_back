<?php

declare(strict_types=1);


namespace App\Services\Order;

use App\Models\Order\OrderWithComment\OrderWithCommentsInterface;
use App\Models\Order\OrderWithTotalComments\OrderWithTotalCommentsInterface;

interface OrderFindWithCommentServiceInterface
{
    public function findWithTotalComments(int $id): ?OrderWithTotalCommentsInterface;

    public function findWithComments(int $id, array $requestParams = []): ?OrderWithCommentsInterface;
}