<?php

declare(strict_types=1);

namespace App\Services\Activity;

interface ActivitiesInterface
{
    public function getActivities(?int $subjectId, array $requestParams = []): array;
}