<?php

namespace Database\Seeders;

use App\Models\Dictionary\Dictionary;
use App\Models\Dictionary\DictionaryTypeEnum;
use Illuminate\Database\Seeder;

final class DictionariesTableSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'id' => 1,
                'type' => DictionaryTypeEnum::PRODUCT_TITLE->value,
                'value' => 'Торт',
            ],
            [
                'id' => 2,
                'type' => DictionaryTypeEnum::PRODUCT_TITLE->value,
                'value' => 'Пирожное',
            ],
            [
                'id' => 3,
                'type' => DictionaryTypeEnum::PRODUCT_TITLE->value,
                'value' => 'Эклер',
            ],
            [
                'id' => 4,
                'type' => DictionaryTypeEnum::PRODUCT_TITLE->value,
                'value' => 'Булочка',
            ],
        ];

        foreach ($items as $item) {
            Dictionary::query()->firstOrCreate(['id' => $item['id']], $item);
        }
    }
}