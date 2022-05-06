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
            ['id' => 3, 'name' => 'roles.delete', 'label' => 'Удаление ролей'],
            ['id' => 4, 'name' => 'users.edit', 'label' => 'Создание и редактирование пользователей'],
            ['id' => 5, 'name' => 'users.view', 'label' => 'Просмотр пользователей'],
            ['id' => 6, 'name' => 'users.delete', 'label' => 'Удаление пользователей'],
            ['id' => 7, 'name' => 'files.view', 'label' => 'Просмотр файлов'],
            ['id' => 8, 'name' => 'files.edit', 'label' => 'Загрузка, удаление файлов'],
            ['id' => 9, 'name' => 'orders.view', 'label' => 'Просмотр заказов'],
            ['id' => 10, 'name' => 'orders.edit', 'label' => 'Редактирование заказов'],
            ['id' => 11, 'name' => 'orders.delete', 'label' => 'Удаление заказов'],
            ['id' => 12, 'name' => 'orders.process', 'label' => 'Продвижение заказов'],
            ['id' => 13, 'name' => 'sellers.view', 'label' => 'Просмотр точек продаж'],
            ['id' => 14, 'name' => 'sellers.edit', 'label' => 'Редактирование точек продаж'],
            ['id' => 15, 'name' => 'sellers.delete', 'label' => 'Удаление точек продаж'],
            ['id' => 16, 'name' => 'manufacturers.view', 'label' => 'Просмотр производителей'],
            ['id' => 17, 'name' => 'manufacturers.edit', 'label' => 'Редактирование производителей'],
            ['id' => 18, 'name' => 'manufacturers.delete', 'label' => 'Удаление производителей'],
            ['id' => 19, 'name' => 'notifications.view', 'label' => 'Просмотр уведомлений'],
        ];

        foreach ($items as $item) {
            Permission::updateOrCreate(['id' => $item['id']], $item);
        }
    }
}
