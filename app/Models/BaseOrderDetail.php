<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Parental\HasChildren;

class BaseOrderDetail extends Model
{
    use HasChildren;
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
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
