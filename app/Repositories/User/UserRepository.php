<?php

declare(strict_types=1);

namespace App\Repositories\User;

use App\Models\User\Sub\Device;
use App\Models\User\Sub\DeviceInterface;
use App\Models\User\User;
use App\Models\User\UserInterface;
use App\Repositories\AbstractRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Laravel\Sanctum\PersonalAccessToken;

final class UserRepository extends AbstractRepository implements UserRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(User::class);
    }

    public function find(int $id): ?Model
    {
        return User::query()->find($id);
    }

    public function findByIds(int ...$ids): Collection
    {
        return User::query()
            ->where('id', $ids)
            ->get();
    }

    public function addExtraFilter(Builder $builder, array &$criteria): void
    {
        if (!isset($criteria['filter']['is_online'])) {
            return;
        }

        $isOnline = match((int) $criteria['filter']['is_online']) {
            User::STATUS_ONLINE => true,
            User::STATUS_OFFLINE => false,
        };

        unset($criteria['filter']['is_online']);

        $now = Carbon::now()->subMinutes(User::ONLINE_STATUS_BORDER)->format('Y-m-d H:i:s');

        if ($isOnline) {
            $builder
                ->whereNotNull('last_seen')
                ->where('last_seen', '>=', $now);
        } else {
            $builder
                ->where('last_seen', '<=', $now)
                ->orWhereNull('last_seen');
        }
    }

    public function profile(int $userId): ?Model
    {
        return User::query()
            ->where('id', $userId)
            ->with('role.permissions', 'vkAccessToken')
            ->get()
            ->first();
    }

    // TODO: Think about this one. Maybe put it in another layer, not in actual repository.
    public function devices(Model $user, ?string $deviceName = null): Collection
    {
        if (!$deviceName) {
            return collect();
        }

        /** @var UserInterface $user */
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
}