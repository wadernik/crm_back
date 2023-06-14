<?php

declare(strict_types=1);

namespace App\Services\Order;

use App\DTOs\Order\CreateOrderDTO;
use App\Exceptions\OrderException;
use App\Managers\Order\OrderManagerInterface;
use App\Models\Order\BaseOrder;
use App\Services\DateLimit\AcceptOrderValidatorServiceInterface;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractOrderCreatorService implements OrderCreatorServiceInterface
{
    private OrderManagerInterface $manager;
    private OrderNumberGeneratorServiceInterface $numberGeneratorService;
    private AcceptOrderValidatorServiceInterface $orderValidatorService;

    public function __construct(private string $managerClass)
    {
        $this->manager = app($this->managerClass);
        $this->numberGeneratorService = app(OrderNumberGeneratorServiceInterface::class);
        $this->orderValidatorService = app(AcceptOrderValidatorServiceInterface::class);
    }

    /**
     * @throws OrderException
     */
    public function create(array $attributes): Model
    {
        if (!isset($attributes['user_id'])) {
            $attributes['user_id'] = auth('sanctum')->id();
        }

        if (!$this->orderValidatorService->canAccept($attributes['manufacturer_id'], $attributes['order_date'])) {
            throw new OrderException(message: __('order.limited_date'));
        }

        $attributes['status'] = BaseOrder::STATUS_ACCEPTED;
        $attributes['number'] = $this->numberGeneratorService->generate($attributes['order_date']);

        return $this->manager->create(new CreateOrderDTO($attributes));
    }
}