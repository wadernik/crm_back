<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderDetail extends Model
{
    use SoftDeletes;

    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'name',
        'amount',
        'label',
        'comment',
    ];

    protected $hidden = [
        'id',
        'order_id',
        'deleted_at',
    ];
}
