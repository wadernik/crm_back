<?php

namespace Database\Seeders;

use App\Models\Manufacturer;
use Illuminate\Database\Seeder;

class ManufacturersTableSeeder extends Seeder
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
                'name' => 'Государевы пекарни производство',
                'address' => 'Строка адреса',
                'phone' => '79111111111',
                'email' => 'fake@email.com',
                'limit' => 10,
            ],
            [
                'id' => 2,
                'name' => 'Тестовое производство, чтобы было',
                'address' => 'Строка адреса',
                'phone' => '79111111112',
                'email' => 'fake@email.com',
                'limit' => 5,
            ],
        ];

        foreach ($items as $item) {
            Manufacturer::firstOrCreate(['id' => $item['id']], $item);
        }
    }
}
