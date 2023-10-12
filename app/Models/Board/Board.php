<?php

declare(strict_types=1);

namespace App\Models\Board;

use App\Models\File\File;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Board extends Model implements BoardInterface
{
    use SoftDeletes;

    protected $table = 'task_boards';

    protected $fillable = [
        'name',
        'file_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function file(): HasOne
    {
        return $this->hasOne(File::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'task_board_user', 'board_id', 'user_id');
    }

    public function groups(): HasMany
    {
        return $this->hasMany(Group::class);
    }
}