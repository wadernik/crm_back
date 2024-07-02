<?php

declare(strict_types=1);

namespace App\Models\Setting;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Setting extends Model implements SettingInterface
{
    use SoftDeletes;

    protected $fillable = [
        'type_id',
        'value',
        'value_type_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}