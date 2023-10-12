<?php

declare(strict_types=1);

namespace App\Models\Board;

use Illuminate\Database\Eloquent\Model;

final class Content extends Model implements ContentInterface
{
    protected $table = 'task_contents';

    protected $fillable = [
        'id',
        'type',
        'name',
        'content',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}