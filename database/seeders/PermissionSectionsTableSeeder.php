<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Permission\PermissionSection;
use Illuminate\Database\Seeder;

final class PermissionSectionsTableSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['id' => 1, 'name' => 'Роли'],
            ['id' => 2, 'name' => 'Пользователи'],
            ['id' => 3, 'name' => 'Точки продаж'],
            ['id' => 4, 'name' => 'Производства'],
            ['id' => 5, 'name' => 'Файлы'],
            ['id' => 6, 'name' => 'Заказы'],
            ['id' => 7, 'name' => 'Заказы. Точки остановки продаж'],
            ['id' => 8, 'name' => 'Уведомления'],
            ['id' => 9, 'name' => 'Отчеты'],
            ['id' => 10, 'name' => 'Логи'],
            ['id' => 11, 'name' => 'Комментарии'],
            ['id' => 12, 'name' => 'Импорт точек продаж'],
            ['id' => 13, 'name' => 'Импорт номенклатуры'],
            ['id' => 14, 'name' => 'Задачи. Доски'],
            ['id' => 15, 'name' => 'Задачи. Группы'],
        ];

        foreach ($items as $item) {
            PermissionSection::query()->updateOrCreate(['id' => $item['id']], $item);
        }
    }
}