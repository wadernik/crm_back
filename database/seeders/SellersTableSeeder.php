<?php

namespace Database\Seeders;

use App\Models\Seller\Seller;
use Illuminate\Database\Seeder;

class SellersTableSeeder extends Seeder
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
                'name' => 'Точка продажи',
                'address' => 'ул. Советская, д. 127',
                'phone' => '79111111111',
                'email' => 'fake@email.com',
            ],
            [
                'id' => 2,
                'name' => 'Точка продажи',
                'address' => 'Ленинский пр-т, д. 22В',
                'phone' => '79111111112',
                'email' => 'fake@email.com',
            ],
            [
                'id' => 3,
                'name' => 'Точка продажи',
                'address' => 'ул. Панфилова, д. 19',
                'phone' => '79111111111',
                'email' => 'fake@email.com',
            ],
            [
                'id' => 4,
                'name' => 'Точка продажи',
                'address' => 'ул. Строителей, д. 38, супермаркет "Пятерочка"',
                'phone' => '79111111111',
                'email' => 'fake@email.com',
            ],
            [
                'id' => 5,
                'name' => 'Точка продажи',
                'address' => 'ул. Машиностроителей, д. 11, супермаркет "Пятерочка"',
                'phone' => '79111111111',
                'email' => 'fake@email.com',
            ],
            [
                'id' => 6,
                'name' => 'Точка продажи',
                'address' => 'ул. Эшкинина, д. 19',
                'phone' => '79111111111',
                'email' => 'fake@email.com',
            ],
            [
                'id' => 7,
                'name' => 'Точка продажи',
                'address' => 'п. Медведево, ул. Советская, д. 30А',
                'phone' => '79111111111',
                'email' => 'fake@email.com',
            ],
        ];

        foreach ($items as $item) {
            Seller::query()->firstOrCreate(['id' => $item['id']], $item);
        }
    }
}