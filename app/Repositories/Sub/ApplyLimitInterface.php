<?php

declare(strict_types=1);

namespace App\Repositories\Sub;

interface ApplyLimitInterface
{
    public function applyLimit(?string $limit = null): void;
}