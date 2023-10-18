<?php

declare(strict_types=1);

namespace Database\Seeders\Board;

use App\Models\Board\Group;
use Illuminate\Database\Seeder;

final class GroupTableSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'id' => 1,
                'board_id' => 1,
                'name' => 'Группа раз',
                'sort' => 1,
            ],
            [
                'id' => 2,
                'board_id' => 1,
                'name' => 'Группа два',
                'sort' => 1,
            ],
        ];

        foreach ($items as $item) {
            Group::query()->firstOrCreate($item);
        }
    }
}