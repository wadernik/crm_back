<?php

declare(strict_types=1);

namespace App\Models\Dictionary;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Dictionary extends Model implements DictionaryInterface
{
    use SoftDeletes;

    protected $fillable = [
        'type',
        'value',
        'uuid',
        'parent_uuid',
        'to_delete',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}