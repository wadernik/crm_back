<?php

declare(strict_types=1);

namespace App\Repositories\Sub;

interface ApplyOffsetInterface
{
    public function applyOffset(?int $limit = null, ?int $offset = null): void;
}