<?php

namespace App\Providers\Order;

use App\Managers\Order\BaseOrderManager;
use App\Managers\Order\BaseOrderManagerInterface;
use App\Managers\Order\Draft\OrderDraftManager;
use App\Managers\Order\Draft\OrderDraftManagerInterface;
use App\Managers\Order\Normal\OrderManager;
use App\Managers\Order\Normal\OrderManagerInterface;
use App\Managers\OrderSetting\OrderSettingManager;
use App\Managers\OrderSetting\OrderSettingManagerInterface;
use Illuminate\Support\ServiceProvider;
use function App\Helpers\Functions\load_service;

class OrderManagerProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(BaseOrderManagerInterface::class, BaseOrderManager::class);
        $this->app->bind(OrderManagerInterface::class, OrderManager::class);
        $this->app->bind(OrderDraftManagerInterface::class, function () {
            return new OrderDraftManager(
                load_service(BaseOrderManagerInterface::class, ['draft' => true])
            );
        });
        $this->app->bind(OrderSettingManagerInterface::class, OrderSettingManager::class);
    }
}