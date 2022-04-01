<?php

namespace App\Models;

use App\Models\Traits\Filterable;
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
    use Filterable;
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use SoftDeletes;

    // In minutes
    private const ONLINE_STATUS_BORDER = 5;

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
        'remember_token',
        'last_seen',
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
        'last_seen',
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
