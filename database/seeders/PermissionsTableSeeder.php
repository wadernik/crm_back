<?php

namespace Database\Seeders;

use App\Models\Permission\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
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
            ['id' => 20, 'name' => 'reports.view', 'label' => 'Возможность просматривать отчеты'],
            ['id' => 21, 'name' => 'users_report.view', 'label' => 'Просмотр отчета по продажам персонала'],
            ['id' => 22, 'name' => 'orders.stop.edit', 'label' => 'Редактирование точек остановки продаж'],
            ['id' => 23, 'name' => 'vk.integration.edit', 'label' => 'Редактирование по интеграции vk'],
            ['id' => 24, 'name' => 'activities.view', 'label' => 'Просмотр логов'],
            ['id' => 25, 'name' => 'comments.view', 'label' => 'Просмотр комментариев'],
            ['id' => 26, 'name' => 'comments.edit', 'label' => 'Редактирование комментариев'],
        ];

        foreach ($items as $item) {
            Permission::query()->updateOrCreate(['id' => $item['id']], $item);
        }
    }
}