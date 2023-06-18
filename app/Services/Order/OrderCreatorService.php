<?php

declare(strict_types=1);

namespace App\Services\Order;

use App\Exceptions\OrderException;
use App\Managers\Order\OrderManagerInterface;
use App\Services\DateLimit\AcceptOrderValidatorServiceInterface;
use Illuminate\Database\Eloquent\Model;

final class OrderCreatorService extends AbstractOrderCreatorService
{
    private AcceptOrderValidatorServiceInterface $orderValidatorService;

    public function __construct()
    {
        parent::__construct(OrderManagerInterface::class);

        $this->orderValidatorService = app(AcceptOrderValidatorServiceInterface::class);
    }

    /**
     * @throws OrderException
     */
    public function create(array $attributes): Model
    {
        if (!$this->orderValidatorService->canAccept($attributes['manufacturer_id'], $attributes['order_date'])) {
            throw new OrderException(message: __('order.limited_date'));
        }

        return parent::create($attributes);
    }
}