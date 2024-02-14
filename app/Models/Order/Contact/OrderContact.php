<?php

declare(strict_types=1);

namespace App\Models\Order\Contact;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderContact extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'order_id',
        'type',
        'value',
    ];

    protected $hidden = [
        'order_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}