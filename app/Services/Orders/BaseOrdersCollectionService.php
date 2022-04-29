<?php

namespace App\Services\Orders;

use App\Services\BaseCollectionService;

abstract class BaseOrdersCollectionService extends BaseCollectionService
{
    /**
     * Retrieve order statuses with captions
     * @return array
     */
    public function getStatuses(): array
    {
        return collect($this->modelClass::statusCaptions())
            ->map(function (string $statusCaption, int $status) {
                return [
                    'id' => $status,
                    'name' => $statusCaption,
                ];
            })
            ->values()
            ->toArray();
    }
}
