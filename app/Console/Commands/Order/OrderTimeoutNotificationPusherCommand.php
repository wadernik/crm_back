<?php

declare(strict_types=1);

namespace App\Console\Commands\Order;

use App\Events\Order\OrderOverdueEvent;
use App\Exceptions\UnexpectedSettingValueTypeException;
use App\Models\Order\OrderStatus;
use App\Models\Setting\Setting;
use App\Models\Setting\SettingTypeEnum;
use App\Models\Setting\SettingValueTypeEnum;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Repositories\Setting\SettingRepositoryInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use function count;

final class OrderTimeoutNotificationPusherCommand extends Command
{
    protected $signature = 'order:overdue:notifier';

    protected $description = 'Create notifications about overdue orders';

    public function __construct(
        private readonly SettingRepositoryInterface $settingRepository,
        private readonly OrderRepositoryInterface $orderRepository
    )
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $orderSetting = $this->settingRepository->findOneByTypeId(
            SettingTypeEnum::idsByEnum()[SettingTypeEnum::ORDER__STATUS_TIMEOUT->value]
        );

        if (!$orderSetting) {
            return;
        }

        $filterDate = $this->prepareFilterDateValue($orderSetting);

        $this->info('Processing overdue orders');

        $orders = $this->orderRepository->findAllBy([
            'filter' => [
                'created_at_end' => $filterDate->format('Y-m-d H:i:s'),
                'status' => OrderStatus::STATUS_CREATED,
            ],
        ]);

        if ($orders->isEmpty()) {
            $this->info('No overdue orders found');

            return;
        }

        $progressBar = $this->output->createProgressBar(count($orders));

        foreach ($orders as $order) {
            OrderOverdueEvent::dispatch($order, $orderSetting->value * 3600);

            $progressBar->advance();
        }

        $progressBar->finish();
    }

    private function prepareFilterDateValue(Setting $setting): Carbon
    {
        $valueType = SettingValueTypeEnum::tryFromId($setting->value_type_id);

        if (!$valueType) {
            throw new UnexpectedSettingValueTypeException();
        }

        return match($valueType) {
            SettingValueTypeEnum::HOUR => Carbon::now()->subHours((int) $setting->value),
            SettingValueTypeEnum::DAY => Carbon::now()->subDays((int) $setting->value),
            SettingValueTypeEnum::WEEK => Carbon::now()->subWeeks((int) $setting->value),
            SettingValueTypeEnum::MONTH => Carbon::now()->subMonths((int) $setting->value),
        };
    }
}