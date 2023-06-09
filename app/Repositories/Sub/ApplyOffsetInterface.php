<?php

declare(strict_types=1);

namespace App\Repositories\Sub;

interface ApplyOffsetInterface
{
    public function applyOffset(?string $limit = null, ?string $offset = null): void;
}