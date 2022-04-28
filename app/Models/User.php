<?php

namespace App\Models;

use App\Models\Traits\FilterableTrait;
use App\Models\Traits\SortableTrait;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use FilterableTrait;
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use SortableTrait;
    use SoftDeletes;

    // In minutes
    public const ONLINE_STATUS_BORDER = 5;
    public const SEX_M = 1;
    public const SEX_W = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'password',
        'role_id',
        'phone',
        'email',
        'birth_date',
        'sex',
        'last_seen',
        'remember_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function isOnline(): Attribute
    {
        return new Attribute(
            get: function () {
                if (!$this->last_seen) {
                    return false;
                }

                $now = Carbon::now()->subMinutes(self::ONLINE_STATUS_BORDER);
                $lastSeenCarbon = Carbon::parse($this->last_seen);

                return !$lastSeenCarbon->lt($now);
            }
        );
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * @return array|null
     */
    public function getUserPermissions(): ?array
    {
        return $this->role->permissions->unique()->pluck('name')->toArray();
    }
}
