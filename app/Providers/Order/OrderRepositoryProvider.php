<?php

namespace App\Providers\Order;

use App\Repositories\Order\Item\OrderItemRepository;
use App\Repositories\Order\Item\OrderItemRepositoryInterface;
use App\Repositories\Order\OrderDraftRepository;
use App\Repositories\Order\OrderDraftRepositoryInterface;
use App\Repositories\Order\OrderRepository;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Repositories\OrderSetting\OrderSettingRepository;
use App\Repositories\OrderSetting\OrderSettingRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class OrderRepositoryProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
        $this->app->bind(OrderDraftRepositoryInterface::class, OrderDraftRepository::class);
        $this->app->bind(OrderItemRepositoryInterface::class, OrderItemRepository::class);
        $this->app->bind(OrderSettingRepositoryInterface::class, OrderSettingRepository::class);
    }
}