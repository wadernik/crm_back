<?php

declare(strict_types=1);

namespace Database\Seeders\Board;

use App\Models\Board\Board;
use Illuminate\Database\Seeder;

final class BoardTableSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'id' => 1,
                'name' => 'Тестовая доска',
            ]
        ];

        foreach ($items as $item) {
            /** @var Board $board */
            $board = Board::query()->firstOrCreate($item);

            $board->users()->sync([1]);
        }
    }
}