<?php

declare(strict_types=1);

namespace App\Services\OrderSetting;

interface OrderSettingTypeRetrieverInterface
{
    public function get(): array;
}