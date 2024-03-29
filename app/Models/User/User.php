<?php

namespace App\Models\User;

use App\Helpers\UserCacheKeys;
use App\Models\Role\Role;
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
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class User extends Authenticatable implements UserInterface
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

    protected $fillable = [
        'first_name',
        'last_name',
        // 'username',
        'password',
        'role_id',
        'phone',
        'email',
        'birth_date',
        'sex',
        'last_seen',
        'remember_token',
        'as_inspector',
    ];

    protected $hidden = [
        'username',
        'password',
        'remember_token',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $appends = [
        'is_online'
    ];

    public static function statuses(): array
    {
        return [
            self::STATUS_ONLINE => __('user.statuses.online'),
            self::STATUS_OFFLINE => __('user.statuses.offline'),
        ];
    }

    protected function isOnline(): Attribute
    {
        return new Attribute(
            get: function (): int {
                $cached = [];

                if (Cache::has(UserCacheKeys::USER_ONLINE)) {
                    $cached = Cache::get(UserCacheKeys::USER_ONLINE);
                }

                if (!$this->last_seen && !isset($cached[$this->id])) {
                    return self::STATUS_OFFLINE;
                }

                $lastSeen = $cached[$this->id] ?? $this->last_seen;

                $now = Carbon::now()->subMinutes(self::ONLINE_STATUS_BORDER);

                $lastSeenCarbon = Carbon::parse($lastSeen);

                return ($lastSeenCarbon->lt($now))
                    ? self::STATUS_OFFLINE
                    : self::STATUS_ONLINE;
            }
        );
    }

    public function getTokens(): Collection
    {
        return $this->tokens()->get(['id', 'name', 'last_used_at']);
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function vkAccessToken(): HasOne
    {
        return $this->hasOne(UserToken::class, 'user_id', 'id');
    }

    public function getUserPermissions(): Collection
    {
        return $this->role->permissions->unique()->pluck('name');
    }

    // Activity Log stuff
    public function needsCommentApproval($model): bool
    {
        return false;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logExcept(['last_seen', 'created_at', 'updated_at'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function receivesBroadcastNotificationsOn(): string
    {
        return "users.{$this->id}";
    }
}