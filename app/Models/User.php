<?php

namespace App\Models;

use App\Models\Traits\FilterableTrait;
use App\Models\Traits\SortableTrait;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class User extends Authenticatable
{
    use FilterableTrait;
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use SortableTrait;
    use SoftDeletes;
    use LogsActivity;

    // In minutes
    public const ONLINE_STATUS_BORDER = 5;

    public const SEX_M = 1;
    public const SEX_W = 2;

    public const STATUS_ONLINE = 1;
    public const STATUS_OFFLINE = 2;

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
                    return self::STATUS_OFFLINE;
                }

                $now = Carbon::now()->subMinutes(self::ONLINE_STATUS_BORDER);
                $lastSeenCarbon = Carbon::parse($this->last_seen);

                return ($lastSeenCarbon->lt($now))
                    ? self::STATUS_OFFLINE
                    : self::STATUS_ONLINE;
            }
        );
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logExcept(['last_seen'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * @return HasOne
     */
    public function vkAccessToken(): HasOne
    {
        return $this->hasOne(UserToken::class, 'user_id', 'id');
    }

    /**
     * @return array|null
     */
    public function getUserPermissions(): ?array
    {
        return $this->role->permissions->unique()->pluck('name')->toArray();
    }

    /**
     * @return array|null
     */
    public function getTokens(): ?array
    {
        return $this->tokens()->get(['id', 'name', 'last_used_at'])->toArray();
    }
}
