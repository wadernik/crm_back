<?php

declare(strict_types=1);

namespace App\Models\OrderSetting;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderSetting extends Model implements OrderSettingInterface
{
    use SoftDeletes;

    protected $fillable = [
        'type_id',
        'value',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}