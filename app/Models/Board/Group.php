<?php

declare(strict_types=1);

namespace App\Models\Board;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Group extends Model implements GroupInterface
{
    use SoftDeletes;

    protected $table = 'task_groups';

    protected $fillable = [
        'board_id',
        'name',
        'sort',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function board(): BelongsTo
    {
        return $this->belongsTo(Board::class);
    }
}