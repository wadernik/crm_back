<?php

declare(strict_types=1);

namespace App\Services\Dooglys\SalePoint;

use App\Jobs\ProcessImportSellersJob;
use App\Services\Dooglys\SalePoint\Sub\SalePoint;
use App\Services\Dooglys\Sub\DooglysSalePointActionInterface;
use Illuminate\Support\Carbon;
use function collect;

final class DooglysSalePointImportService implements DooglysSalePointImportServiceInterface
{
    public function __construct(private readonly DooglysSalePointActionInterface $dooglysSalePointAction)
    {
    }

    public function import(): void
    {
        $salePoints = [];
        $simpleCounter = 1;

        foreach (collect($this->dooglysSalePointAction->salePoints())->chunk(20) as $chunk) {
            foreach ($chunk as $point) {
                $salePoints[] = new SalePoint([
                    'id' => $point['id'],
                    'menu_id' => $point['menu_id'],
                    'name' => $point['name'],
                    'phone_number' => $point['phone_number'],
                    'address' => [
                        'name' => $point['address']['name'] ?? null,
                        'lat' => !empty($point['address']['lat']) ? (string) $point['address']['lat'] : null,
                        'long' => !empty($point['address']['long']) ? (string) $point['address']['long'] : null,
                    ],
                ]);
            }

            ProcessImportSellersJob::dispatch($salePoints, Carbon::now()->format('Y-m-d-H') . "-{$simpleCounter}");

            $salePoints = [];
            $simpleCounter++;
        }

    }
}