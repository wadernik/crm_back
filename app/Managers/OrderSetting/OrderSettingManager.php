<?php

declare(strict_types=1);

namespace App\Managers\OrderSetting;

use App\DTOs\OrderSetting\CreateOrderSettingDTOInterface;
use App\Models\OrderSetting\OrderSetting;

final class OrderSettingManager implements OrderSettingManagerInterface
{
    public function create(CreateOrderSettingDTOInterface $orderSettingDTO): OrderSetting
    {
        /** @var OrderSetting $orderSetting */
        $orderSetting = OrderSetting::query()->create($orderSettingDTO->toArray());

        return $orderSetting;
    }

    public function update(OrderSetting $orderSetting, CreateOrderSettingDTOInterface $orderSettingDTO): OrderSetting
    {
        $orderSetting->update($orderSettingDTO->toArray());

        return $orderSetting;
    }

    public function delete(OrderSetting $orderSetting): OrderSetting
    {
        $orderSetting->delete();

        return $orderSetting;
    }
}