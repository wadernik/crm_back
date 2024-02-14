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
            ['section_id' => 1, 'name' => 'roles.edit', 'label' => 'Создание и назначение'],
            ['section_id' => 1, 'name' => 'roles.view', 'label' => 'Просмотр'],
            ['section_id' => 1, 'name' => 'roles.delete', 'label' => 'Удаление'],
            ['section_id' => 2, 'name' => 'users.edit', 'label' => 'Создание и редактирование'],
            ['section_id' => 2, 'name' => 'users.view', 'label' => 'Просмотр'],
            ['section_id' => 2, 'name' => 'users.delete', 'label' => 'Удаление'],
            ['section_id' => 3, 'name' => 'sellers.view', 'label' => 'Просмотр'],
            ['section_id' => 3, 'name' => 'sellers.edit', 'label' => 'Редактирование'],
            ['section_id' => 3, 'name' => 'sellers.delete', 'label' => 'Удаление'],
            ['section_id' => 4, 'name' => 'manufacturers.view', 'label' => 'Просмотр'],
            ['section_id' => 4, 'name' => 'manufacturers.edit', 'label' => 'Редактирование'],
            ['section_id' => 4, 'name' => 'manufacturers.delete', 'label' => 'Удаление'],
            ['section_id' => 5, 'name' => 'files.view', 'label' => 'Просмотр'],
            ['section_id' => 5, 'name' => 'files.edit', 'label' => 'Загрузка, удаление'],
            ['section_id' => 6, 'name' => 'orders.view', 'label' => 'Просмотр'],
            ['section_id' => 6, 'name' => 'orders.edit', 'label' => 'Редактирование'],
            ['section_id' => 6, 'name' => 'orders.delete', 'label' => 'Удаление'],
            ['section_id' => 6, 'name' => 'orders.process', 'label' => 'Продвижение'],
            ['section_id' => 7, 'name' => 'orders.stop.edit', 'label' => 'Редактирование'],
            ['section_id' => 8, 'name' => 'notifications.view', 'label' => 'Просмотр'],
            ['section_id' => 9, 'name' => 'reports.view', 'label' => 'Просмотр отчетов'],
            ['section_id' => 9, 'name' => 'users_report.view', 'label' => 'Просмотр отчетов по продажам персонала'],
            ['section_id' => 10, 'name' => 'activities.view', 'label' => 'Просмотр'],
            ['section_id' => 11, 'name' => 'comments.view', 'label' => 'Просмотр'],
            ['section_id' => 11, 'name' => 'comments.edit', 'label' => 'Редактирование'],
            ['section_id' => 12, 'name' => 'sellers.import', 'label' => 'Импорт'],
            ['section_id' => 13, 'name' => 'menu.import', 'label' => 'Импорт'],
            ['section_id' => 14, 'name' => 'boards.board.view', 'label' => 'Просмотр'],
            ['section_id' => 14, 'name' => 'boards.board.edit', 'label' => 'Редактирование'],
            ['section_id' => 15, 'name' => 'boards.group.view', 'label' => 'Просмотр групп'],
            ['section_id' => 15, 'name' => 'boards.group.edit', 'label' => 'Редактирование групп'],
            ['section_id' => 16, 'name' => 'orders.settings.view', 'label' => 'Просмотр настроек по заказам'],
            ['section_id' => 16, 'name' => 'orders.settings.edit', 'label' => 'Редактирование настроек по заказам'],
        ];

        $id = 1;

        foreach ($items as $item) {
            $item['id'] = $id;

            Permission::query()->updateOrCreate(['id' => $item['id']], $item);

            $id++;
        }
    }
}