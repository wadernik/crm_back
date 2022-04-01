<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            ['id' => 1, 'name' => 'roles.edit', 'label' => 'Создание и назначение ролей'],
            ['id' => 2, 'name' => 'roles.view', 'label' => 'Просмотр ролей'],
            ['id' => 3, 'name' => 'orders.view', 'label' => 'Просмотр заказов'],
            ['id' => 4, 'name' => 'users.edit', 'label' => 'Создание и редактирование пользователей'],
            ['id' => 5, 'name' => 'users.view', 'label' => 'Просмотр пользователей'],
        ];

        foreach ($items as $item) {
            Permission::updateOrCreate(['id' => $item['id']], $item);
        }
    }
}
