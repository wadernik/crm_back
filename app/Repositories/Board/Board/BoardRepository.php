<?php

declare(strict_types=1);

namespace App\Repositories\Board\Board;

use App\Models\Board\Board;
use App\Repositories\AbstractRepository;
use Illuminate\Database\Eloquent\Builder;

final class BoardRepository extends AbstractRepository implements BoardRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(Board::class);
    }

    public function addExtraFilter(Builder $builder, array &$criteria): void
    {
    }

    public function find(int $id): ?Board
    {
        /** @var Board $board */
        $board = Board::query()->find($id);

        return $board;
    }
}