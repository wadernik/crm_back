<?php

declare(strict_types=1);

namespace App\Console\Commands\Order;

use App\Events\Order\OrderOverdueEvent;
use App\Models\Order\OrderStatus;
use App\Models\OrderSetting\OrderSettingTypeEnum;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Repositories\OrderSetting\OrderSettingRepositoryInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use function count;

final class OrderTimeoutNotificationPusherCommand extends Command
{
    protected $signature = 'order:overdue:notifier';

    protected $description = 'Create notifications about overdue orders';

    public function __construct(
        private readonly OrderSettingRepositoryInterface $orderSettingRepository,
        private readonly OrderRepositoryInterface $orderRepository
    )
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $orderSetting = $this->orderSettingRepository->findOneByType(OrderSettingTypeEnum::STATUS_TIMEOUT);

        if (!$orderSetting) {
            return;
        }

        $filterDate = Carbon::now()->startOfDay()->subDays((int) $orderSetting->value);

        $this->info('Processing overdue orders');

        $orders = $this->orderRepository->findAllBy([
            'filter' => [
                'order_date_end' => $filterDate->format('Y-m-d H:i:s'),
                'status' => OrderStatus::STATUS_CREATED,
            ],
        ]);

        if ($orders->isEmpty()) {
            $this->info('No overdue orders found');

            return;
        }

        $progressBar = $this->output->createProgressBar(count($orders));

        foreach ($orders as $order) {
            OrderOverdueEvent::dispatch($order);

            $progressBar->advance();
        }

        $progressBar->finish();
    }
}