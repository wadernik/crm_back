<?php

declare(strict_types=1);

namespace App\Services\Order\Activity;

interface OrderActivityServiceInterface
{
    public function activities(?int $subjectId = null, array $requestParams = []): array;
}