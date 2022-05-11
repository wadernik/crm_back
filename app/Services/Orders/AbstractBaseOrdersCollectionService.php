<?php

namespace App\Services\Orders;

use App\Services\AbstractBaseCollectionService;

abstract class AbstractBaseOrdersCollectionService extends AbstractBaseCollectionService
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
