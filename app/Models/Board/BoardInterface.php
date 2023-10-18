<?php

declare(strict_types=1);

namespace App\Models\Board;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int     $id
 * @property string  $name
 * @property int     $file_id
 * @property User[]  $users
 * @property Group[] $groups
 */
interface BoardInterface
{
    public function file(): HasOne;

    public function users(): BelongsToMany;

    public function canUser(int $userId): bool;
}