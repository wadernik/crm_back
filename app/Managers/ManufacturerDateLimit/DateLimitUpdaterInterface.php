<?php

declare(strict_types=1);

namespace App\Managers\ManufacturerDateLimit;

use App\DTOs\ManufacturerDateLimit\UpdateDateLimitDTOInterface;
use Illuminate\Database\Eloquent\Model;

interface DateLimitUpdaterInterface
{
    public function update(int $id, UpdateDateLimitDTOInterface $dateLimitDTO): ?Model;
}