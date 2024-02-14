<?php

declare(strict_types=1);

namespace App\Managers\OrderSetting;

use App\DTOs\OrderSetting\CreateOrderSettingDTOInterface;
use App\Models\OrderSetting\OrderSetting;

interface OrderSettingUpdaterInterface
{
    public function update(OrderSetting $orderSetting, CreateOrderSettingDTOInterface $orderSettingDTO): OrderSetting;
}