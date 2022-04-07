<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'filename',
        'path',
        'extension',
    ];

    protected $hidden = [
        'created_at',
        'update_at',
        'deleted_at',
    ];
}
