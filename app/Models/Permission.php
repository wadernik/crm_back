<?php

namespace App\Models;

use App\Models\Traits\FilterableTrait;
use App\Models\Traits\SortableTrait;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use FilterableTrait;
    use SortableTrait;

    public $timestamps = false;

    protected $hidden = ['pivot'];
}
