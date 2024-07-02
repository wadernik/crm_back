<?php

declare(strict_types=1);

namespace App\Console\Commands\Notification;

use App\Exceptions\UnexpectedSettingValueTypeException;
use App\Managers\Notification\DatabaseNotificationManagerInterface;
use App\Models\Setting\Setting;
use App\Models\Setting\SettingTypeEnum;
use App\Models\Setting\SettingValueTypeEnum;
use App\Repositories\Setting\SettingRepositoryInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

final class ClearIrrelevantUnreadNotificationsCommand extends Command
{
    protected $signature = 'notification:unread:clear';

    protected $description = 'Clear old irrelevant unread notifications';

    public function __construct(
        private readonly SettingRepositoryInterface $settingRepository,
        private readonly DatabaseNotificationManagerInterface $databaseNotificationManager,
    )
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $setting = $this->settingRepository->findOneByTypeId(
            SettingTypeEnum::id(SettingTypeEnum::NOTIFICATION__UNREAD_TIMEOUT->value)
        );

        if (!$setting) {
            return;
        }

        $filterDate = $this->prepareFilterDateValue($setting);

        $this->info('Clearing unread notifications...');

        $this->databaseNotificationManager->clearUnreadByDate($filterDate->format('Y-m-d H:i:s'));

        $this->info('Done.');
    }

    private function prepareFilterDateValue(Setting $setting): Carbon
    {
        $valueType = SettingValueTypeEnum::tryFromId($setting->value_type_id);

        if (!$valueType) {
            throw new UnexpectedSettingValueTypeException();
        }

        return match($valueType) {
            SettingValueTypeEnum::HOUR => Carbon::now()->subHours((int) $setting->value),
            SettingValueTypeEnum::DAY => Carbon::now()->startOfDay()->subDays((int) $setting->value),
            SettingValueTypeEnum::WEEK => Carbon::now()->startOfDay()->subWeeks((int) $setting->value),
            SettingValueTypeEnum::MONTH => Carbon::now()->startOfDay()->subMonths((int) $setting->value),
        };
    }
}