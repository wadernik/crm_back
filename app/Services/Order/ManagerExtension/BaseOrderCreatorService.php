<?php

declare(strict_types=1);

namespace App\Services\Order\ManagerExtension;

use App\Exceptions\OrderException;
use App\Managers\Order\BaseOrderManagerInterface;
use App\Models\Order\Order;
use App\Models\Order\OrderStatus;
use App\Services\Order\Checker\OrderCreationRestrictionByManufacturerCheckerInterface;
use App\Services\Order\OrderNumber\OrderNumberGeneratorServiceInterface;
use App\Services\Order\Processor\OrderInspectorProcessorInterface;
use function __;
use function auth;

final class BaseOrderCreatorService implements BaseOrderCreatorServiceInterface
{
    public function __construct(
        private readonly BaseOrderManagerInterface $manager,
        private readonly OrderCreationRestrictionByManufacturerCheckerInterface $orderCreationRestrictionChecker,
        private readonly OrderNumberGeneratorServiceInterface $numberGeneratorService,
        private readonly OrderInspectorProcessorInterface $orderInspectorProcessor,
        private readonly string $dtoClass,
    )
    {
    }

    public function create(array $attributes): Order
    {
        if (!$this->orderCreationRestrictionChecker->check(
            $attributes['manufacturer_id'] ?? null,
            $attributes['order_date'] ?? null
        )) {
            throw new OrderException(message: __('order.limited_date'));
        }

        if (!isset($attributes['user_id'])) {
            $attributes['user_id'] = auth('sanctum')->id();
        }

        if (!isset($attributes['inspector_id'])) {
            $attributes['inspector_id'] = $this->orderInspectorProcessor->process();
        }

        $attributes['status'] = OrderStatus::STATUS_CREATED;
        $attributes['number'] = $this->numberGeneratorService->generate($attributes['order_date']);

        return $this->manager->create(new $this->dtoClass($attributes));
    }
}