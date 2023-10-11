<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Services\Dooglys\Menu\DooglysMenuImportServiceInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

final class ImportMenuJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;

    public function handle(DooglysMenuImportServiceInterface $dooglysMenuImportService): void
    {
        $dooglysMenuImportService->import();
    }
}