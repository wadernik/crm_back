<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Seller\Seller;
use App\Repositories\Seller\SellerRepositoryInterface;
use App\Services\Dooglys\SalePoint\Sub\SalePoint;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use function array_filter;

final class ProcessImportSellersJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;

    public int $tries = 5;

    /**
     * @param SalePoint[] $points
     * @param string      $uniqueId
     */
    public function __construct(private readonly array $points, private readonly string $uniqueId)
    {
    }

    public function handle(SellerRepositoryInterface $sellerRepository): void
    {
        $salePointsUuids = collect($this->points)
            ->pluck('id')
            ->toArray();

        $existingSellers = $sellerRepository->findAllBy(['uuids' => $salePointsUuids]);

        $existingSellers = $existingSellers->mapWithKeys(static function (Seller $seller): array {
            return [$seller->uuid ?? (string) $seller->id => $seller];
        });

        foreach ($this->points as $point) {
            $attributes = array_filter([
                'name' => $point->name(),
                'phone' => $point->phone(),
                'address' => $point->address()->name(),
                'latitude' => $point->address()->latitude(),
                'longitude' => $point->address()->longitude(),
                'menu_id' => $point->menu(),
                'uuid' => $point->id(),
            ]);

            if ($existingSellers->has($point->id())) {
                /** @var Seller $seller */
                $seller = $existingSellers->get($point->id());

                $seller->update($attributes);

                continue;
            }

            $seller = new Seller($attributes);

            $seller->save();
        }
    }

    public function uniqueId(): string
    {
        return $this->uniqueId;
    }
}