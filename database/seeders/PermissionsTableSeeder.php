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
            ['id' => 1, 'name' => 'roles.edit', 'label' => 'Создание и редактирование ролей'],
            ['id' => 2, 'name' => 'roles.view', 'label' => 'Просмотр ролей'],
            ['id' => 3, 'name' => 'orders.view', 'label' => 'Просмотр заказов'],
        ];

        foreach ($items as $item) {
            Permission::updateOrCreate(['id' => $item['id']], $item);
        }
    }
}
