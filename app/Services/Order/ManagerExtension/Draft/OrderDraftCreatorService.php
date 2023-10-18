<?php

declare(strict_types=1);

namespace App\Services\Order\ManagerExtension\Draft;

use App\DTOs\Order\OrderDraftDTO;
use App\Managers\Order\Draft\OrderDraftManagerInterface;
use App\Models\Order\Order;
use App\Services\Order\ManagerExtension\BaseOrderCreatorServiceInterface;
use function App\Helpers\Functions\load_service;

final class OrderDraftCreatorService implements OrderDraftCreatorServiceInterface
{
    private readonly OrderDraftCreatorServiceInterface $innerService;

    public function __construct()
    {
        $this->innerService = load_service(
            BaseOrderCreatorServiceInterface::class,
            [
                'managerClass' => OrderDraftManagerInterface::class,
                'dtoClass' => OrderDraftDTO::class,
            ]
        );
    }

    public function create(array $attributes): Order
    {
        return $this->innerService->create($attributes);
    }
}