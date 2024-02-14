<?php

declare(strict_types=1);

namespace App\Managers\OrderSetting;

interface OrderSettingManagerInterface extends OrderSettingCreatorInterface,
                                               OrderSettingUpdaterInterface,
                                               OrderSettingDeleterInterface
{
}