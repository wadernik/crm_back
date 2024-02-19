<?php

namespace Database\Seeders;

use App\Models\OrderSetting\OrderSetting;
use Illuminate\Database\Seeder;

class OrderSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $items = [
            ['id' => 1, 'type_id' => 1, 'value' => 15],
        ];

        foreach ($items as $item) {
            OrderSetting::query()->firstOrCreate(['id' => $item['id']], $item);
        }
    }
}