<?php

declare(strict_types=1);

namespace App\DTOs\Seller;

interface CreatedByAwareInterface
{
    public function setCreatedBy(?int $createdBy = null): void;
}