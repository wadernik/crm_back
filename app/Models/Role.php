<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use Filterable;

    public $timestamps = false;

    protected $fillable = ['name'];

    protected $hidden = ['pivot'];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permission');
    }
}
