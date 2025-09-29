<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Dictionary\Dictionary;
use App\Models\Dictionary\DictionaryTypeEnum;
use App\Repositories\Dictionary\DictionaryRepositoryInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

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
        /** @var Collection<Dictionary> $existingRecords */
        $existingRecords = $dictionaryRepository
            ->findAllBy(
                ['filter' => ['type' => DictionaryTypeEnum::PRODUCT_TITLE->value]]
            )
            ->mapWithKeys(static function (Dictionary $dictionary): array {
                return [$dictionary->uuid ?? (string) $dictionary->id => $dictionary];
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
                'parent_uuid' => $this->menuId,
            ];

            if ($existingRecords->has($product['id'])) {
                /** @var Dictionary $dictionary */
                $dictionary = $existingRecords->get($product['id']);

                $dictionary->update($attributes);

                $existingRecords->forget($product['id']);

                continue;
            }

            $dictionary = new Dictionary($attributes);

            $dictionary->save();
        }

        foreach ($existingRecords as $entity) {
            if ($entity->parent_uuid === $this->menuId) {
                $entity->to_delete = true;

                $entity->save();
            }
        }
    }

    public function uniqueId(): string
    {
        return $this->menuId;
    }
}
