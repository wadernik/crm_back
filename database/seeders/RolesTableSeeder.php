<?php

namespace Database\Seeders;

use App\Models\Role\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $items = [
            ['id' => Role::ROLE_ADMIN, 'name' => 'Администратор'],
            ['id' => 2, 'name' => 'Менеджер'],
            ['id' => 3, 'name' => 'Продавец-кассир'],
            ['id' => 4, 'name' => 'Пекарь'],
            ['id' => 5, 'name' => 'Кондитер'],
            ['id' => 6, 'name' => 'Кондитер-оформитель'],
        ];

        foreach ($items as $item) {
            Role::query()->firstOrCreate(['id' => $item['id']], $item);
        }
    }
}