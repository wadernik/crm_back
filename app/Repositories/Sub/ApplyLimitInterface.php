<?php

declare(strict_types=1);

namespace App\Repositories\Sub;

interface ApplyLimitInterface
{
    public function applyLimit(?int $limit = null): void;
}