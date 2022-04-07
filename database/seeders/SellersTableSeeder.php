<?php

namespace Database\Seeders;

use App\Models\Seller;
use Illuminate\Database\Seeder;

class SellersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            [
                'id' => 1,
                'name' => 'Точка продажи 1',
                'address' => 'Строка адреса',
                'phone' => '79111111111',
                'email' => 'fake@email.com',
            ],
            [
                'id' => 2,
                'name' => 'Точка продажи 2',
                'address' => 'Строка адреса',
                'phone' => '79111111111',
                'email' => 'fake@email.com',
            ],
            [
                'id' => 2,
                'name' => 'Точка продажи 3',
                'address' => 'Строка адреса',
                'phone' => '79111111111',
                'email' => 'fake@email.com',
            ],
        ];

        foreach ($items as $item) {
            Seller::firstOrCreate(['id' => $item['id']], $item);
        }
    }
}
