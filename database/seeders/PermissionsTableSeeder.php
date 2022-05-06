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
            ['id' => 3, 'name' => 'users.edit', 'label' => 'Создание и редактирование пользователей'],
            ['id' => 4, 'name' => 'users.view', 'label' => 'Просмотр пользователей'],
            ['id' => 5, 'name' => 'files.view', 'label' => 'Просмотр файлов'],
            ['id' => 6, 'name' => 'files.edit', 'label' => 'Загрузка, удаление файлов'],
            ['id' => 7, 'name' => 'orders.view', 'label' => 'Просмотр заказов'],
            ['id' => 8, 'name' => 'orders.edit', 'label' => 'Редактирование заказов'],
            ['id' => 9, 'name' => 'orders.delete', 'label' => 'Удаление заказов'],
            ['id' => 10, 'name' => 'orders.process', 'label' => 'Продвижение заказов'],
            ['id' => 11, 'name' => 'sellers.view', 'label' => 'Просмотр точек продаж'],
            ['id' => 12, 'name' => 'sellers.edit', 'label' => 'Редактирование точек продаж'],
            ['id' => 13, 'name' => 'manufacturers.view', 'label' => 'Просмотр производителей'],
            ['id' => 14, 'name' => 'manufacturers.edit', 'label' => 'Редактирование производителей'],
            ['id' => 15, 'name' => 'notifications.view', 'label' => 'Просмотр уведомлений'],
        ];

        foreach ($items as $item) {
            Permission::updateOrCreate(['id' => $item['id']], $item);
        }
    }
}
