<?php

declare(strict_types=1);

namespace App\Models\Board;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int    $id
 * @property int    $board_id
 * @property string $name
 * @property int    $sort
 * @property Board  $board
 */
interface GroupInterface
{
    public function board(): BelongsTo;
}