<?php

declare(strict_types=1);

namespace App\Services\Activity;

interface ActivityServiceInterface
{
    public function activities(?int $subjectId = null, array $requestParams = []): array;
}