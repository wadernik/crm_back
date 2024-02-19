<?php

declare(strict_types=1);

namespace App\Repositories\User;

use App\Helpers\UserCacheKeys;
use App\Models\User\Sub\Device;
use App\Models\User\Sub\DeviceInterface;
use App\Models\User\User;
use App\Repositories\AbstractRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Laravel\Sanctum\PersonalAccessToken;
use function collect;

final class UserRepository extends AbstractRepository implements UserRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(User::class);
    }

    public function find(int $id): ?User
    {
        /** @var User $user */
        $user = User::query()->find($id);

        return $user;
    }

    public function findByIds(int ...$ids): Collection
    {
        return User::query()
            ->where('id', $ids)
            ->get();
    }

    public function findAll(): Collection
    {
        return User::all();
    }

    public function addExtraFilter(Builder $builder, array &$criteria): void
    {
        if (isset($criteria['filter']['first_name'])) {
            $builder
                ->where('first_name', 'LIKE', "%{$criteria['filter']['first_name']}%")
                ->orWhere('last_name', 'LIKE', "%{$criteria['filter']['first_name']}%");
        }

        if (isset($criteria['filter']['last_name'])) {
            $builder
                ->where('last_name', 'LIKE', "%{$criteria['filter']['last_name']}%")
                ->orWhere('first_name', 'LIKE', "%{$criteria['filter']['last_name']}%");
        }

        unset($criteria['filter']['first_name'], $criteria['filter']['last_name']);

        if (!isset($criteria['filter']['is_online'])) {
            return;
        }

        $isOnline = match((int) $criteria['filter']['is_online']) {
            default => true,
            User::STATUS_OFFLINE => false,
        };

        unset($criteria['filter']['is_online']);

        $usersIds = collect(Cache::get(UserCacheKeys::USER_ONLINE))
            ->keys()
            ->unique()
            ->toArray();

        if ($isOnline) {
            $builder
                ->whereIn('id', $usersIds);
        } else {
            $now = Carbon::now()->subMinutes(User::ONLINE_STATUS_BORDER)->format('Y-m-d H:i:s');

            $builder
                ->whereNotIn('id', $usersIds)
                ->where(static function (Builder $query) use ($now): void {
                    $query
                        ->Where('last_seen', '<=', $now)
                        ->orWhereNull('last_seen');
                });
        }
    }

    public function profile(int $userId): ?User
    {
        return User::query()
            ->where('id', $userId)
            ->with('role.permissions', 'vkAccessToken')
            ->get()
            ->first();
    }

    // TODO: Think about this one. Maybe put it in another layer, not in actual repository.
    public function devices(User $user, ?string $deviceName = null): Collection
    {
        if (!$deviceName) {
            return collect();
        }

        return collect($user->getTokens())
            ->map(static function (PersonalAccessToken $token) use ($deviceName): DeviceInterface {
                $lastUsedAt = Carbon::parse($token['last_used_at'])->format('d.m.Y H:i');

                return new Device($token->id, $token->name, $lastUsedAt, $token->name === $deviceName);
            });
    }

    public function statuses(): array
    {
        return collect(User::statuses())
            ->map(function (string $statusCaption, int $status) {
                return [
                    'id' => $status,
                    'name' => $statusCaption,
                ];
            })
            ->values()
            ->toArray();
    }

    public function findAllByInspector(bool $asInspector = false): Collection
    {
        return User::query()
            ->where('as_inspector', $asInspector)
            ->orderBy('id', 'desc')
            ->get();
    }
}