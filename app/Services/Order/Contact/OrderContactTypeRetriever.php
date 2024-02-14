<?php

declare(strict_types=1);

namespace App\Services\Order\Contact;

use App\Models\Order\Contact\ContactTypeEnum;
use function collect;

final class OrderContactTypeRetriever implements OrderContactTypeRetrieverInterface
{
    public function get(): array
    {
        return collect(ContactTypeEnum::captions())
            ->map(function (string $caption, $key) {
                return [
                    'id' => $key,
                    'name' => $key,
                    'description' => $caption,
                ];
            })
            ->values()
            ->toArray();
    }
}