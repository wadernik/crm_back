<?php

declare(strict_types=1);

namespace App\Models\Board;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int    $id
 * @property int    $group_id
 * @property int    $sort,
 * @property string $name
 * @property string $description
 * @property string $date_from
 * @property string $date_to
 * @property string $time_to
 */
interface TaskInterface
{
    public function group(): BelongsTo;

    public function attachments(): HasMany;
}