<?php

declare(strict_types=1);

namespace App\Managers\OrderSetting;

use App\Models\OrderSetting\OrderSetting;

interface OrderSettingDeleterInterface
{
    public function delete(OrderSetting $orderSetting): OrderSetting;
}