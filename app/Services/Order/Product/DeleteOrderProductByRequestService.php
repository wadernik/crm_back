<?php

declare(strict_types=1);

namespace App\Services\Order\Product;

use App\Http\Requests\Orders\OrderProductRequest;
use App\Models\Dictionary\Dictionary;
use App\Repositories\Dictionary\DictionaryRepositoryInterface;

final class DeleteOrderProductByRequestService implements DeleteOrderProductByRequestServiceInterface
{
    public function __construct(private readonly DictionaryRepositoryInterface $dictionaryRepository)
    {
    }

    public function delete(OrderProductRequest $request): void
    {
        $ids = $request->validated()['titles'] ?? [];

        if (!$ids) {
            return;
        }

        /** @var array<Dictionary> $items */
        $items = $this->dictionaryRepository->findAllBy(['filter' => ['ids' => $ids]]);

        foreach ($items as $item) {
            $item->delete();

            $item->save();
        }
    }
}
