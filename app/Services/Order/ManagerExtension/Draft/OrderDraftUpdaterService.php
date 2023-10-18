<?php

declare(strict_types=1);

namespace App\Services\Order\ManagerExtension\Draft;

use App\DTOs\Order\OrderDraftDTO;
use App\Managers\Order\Draft\OrderDraftManagerInterface;
use App\Models\Order\Order;
use App\Services\Order\ManagerExtension\BaseOrderUpdaterServiceInterface;
use function App\Helpers\Functions\load_service;

final class OrderDraftUpdaterService implements OrderDraftUpdaterServiceInterface
{
    private readonly BaseOrderUpdaterServiceInterface $innerService;

    public function __construct()
    {
        $this->innerService = load_service(
            BaseOrderUpdaterServiceInterface::class,
            [
                'managerClass' => OrderDraftManagerInterface::class,
                'dtoClass' => OrderDraftDTO::class,
            ]
        );
    }

    public function update(Order $order, array $attributes): Order
    {
        return $this->innerService->update($order, $attributes);
    }
}