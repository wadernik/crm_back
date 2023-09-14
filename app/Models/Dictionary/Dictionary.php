<?php

declare(strict_types=1);

namespace App\Models\Dictionary;

use Illuminate\Database\Eloquent\Model;

final class Dictionary extends Model
{
    protected $fillable = [
        'type',
        'value',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}