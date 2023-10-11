<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Services\Dooglys\SalePoint\DooglysSalePointImportServiceInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

final class ImportSellersJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;

    public function handle(DooglysSalePointImportServiceInterface $dooglysSalePointService): void
    {
        $dooglysSalePointService->import();
    }
}