<?php

declare(strict_types=1);


namespace App\Services\User;

use App\DTOs\User\UserReportDTOInterface;
use Illuminate\Support\Collection;

interface UserReportServiceInterface
{
    public function report(UserReportDTOInterface $reportDTO): Collection;
}