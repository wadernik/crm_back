<?php

namespace Database\Seeders;

use App\Models\Setting\Setting;
use App\Models\Setting\SettingTypeEnum;
use App\Models\Setting\SettingValueTypeEnum;
use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $items = [
            [
                'id' => 1,
                'type_id' => SettingTypeEnum::id(SettingTypeEnum::ORDER__STATUS_TIMEOUT->value),
                'value' => 15,
                'value_type_id' => SettingValueTypeEnum::id(SettingValueTypeEnum::HOUR->value),
            ],
            [
                'id' => 2,
                'type_id' => SettingTypeEnum::id(SettingTypeEnum::NOTIFICATION__UNREAD_TIMEOUT->value),
                'value' => 14,
                'value_type_id' => SettingValueTypeEnum::id(SettingValueTypeEnum::DAY->value),
            ],
            [
                'id' => 3,
                'type_id' => SettingTypeEnum::id(SettingTypeEnum::NOTIFICATION__READ_TIMEOUT->value),
                'value' => 7,
                'value_type_id' => SettingValueTypeEnum::id(SettingValueTypeEnum::DAY->value),
            ],
        ];

        foreach ($items as $item) {
            Setting::query()->firstOrCreate(['id' => $item['id']], $item);
        }
    }
}