<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;

class Manufacturer extends Model
{
    use Filterable;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'address',
        'phone',
        'email',
        'limit',
    ];
}
