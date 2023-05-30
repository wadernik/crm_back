<?php

declare(strict_types=1);

namespace App\Managers\ManufacturerDateLimit;

use App\DTOs\ManufacturerDateLimit\CreateDateLimitDTOInterface;
use Illuminate\Database\Eloquent\Model;

interface DateLimitCreatorInterface
{
    public function create(CreateDateLimitDTOInterface $dateLimitDTO): Model;
}