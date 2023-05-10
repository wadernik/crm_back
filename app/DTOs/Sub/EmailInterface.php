<?php

declare(strict_types=1);

namespace App\DTOs\Sub;

interface EmailInterface
{
    public function email(): ?string;
}