<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Dictionary\Dictionary;
use App\Models\Dictionary\DictionaryTypeEnum;
use App\Repositories\Dictionary\DictionaryRepositoryInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use function collect;

final class ProcessImportMenuJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;

    public int $tries = 5;

    /**
     * @param array<array{
     *     id: string,
     *     name: string,
     *     status: string
     * }> $products
     */
    public function __construct(private readonly array $products, private readonly string $menuId)
    {
    }

    public function handle(DictionaryRepositoryInterface $dictionaryRepository): void
    {
        $uuids = collect($this->products)
            ->pluck('id')
            ->unique()
            ->toArray();

        $dictionaries = $dictionaryRepository->findAllBy(
                ['filter' => [
                    'type' => DictionaryTypeEnum::PRODUCT_TITLE->value,
                    'uuids' => $uuids,
                    'deleted_at' => null,
                ]]
            )
            ->mapWithKeys(static function (Dictionary $dictionary): array {
                return [$dictionary->uuid => $dictionary];
            });

        $processedProducts = [];

        foreach ($this->products as $product) {
            if ($product['status'] !== 'enabled') {
                continue;
            }

            if (isset($processedProducts[$product['id']])) {
                continue;
            }

            $processedProducts[$product['id']] = true;

            $attributes = [
                'type' => DictionaryTypeEnum::PRODUCT_TITLE->value,
                'value' => $product['name'],
                'uuid' => $product['id'],
            ];

            if ($dictionaries->has($product['id'])) {
                /** @var Dictionary $dictionary */
                $dictionary = $dictionaries->get($product['id']);

                $dictionary->update($attributes);

                continue;
            }

            $dictionary = new Dictionary($attributes);

            $dictionary->save();
        }
    }

    public function uniqueId(): string
    {
        return $this->menuId;
    }
}