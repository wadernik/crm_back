<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use Filterable;

    public $timestamps = false;

    protected $hidden = ['pivot'];
}
