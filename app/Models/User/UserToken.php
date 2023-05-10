<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserToken extends Model implements UserTokenInterface
{
    use SoftDeletes;

    protected $table = 'user_token';

    protected $fillable = [
        'user_id',
        'token'
    ];

    protected $hidden = [
        'id',
        'user_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}