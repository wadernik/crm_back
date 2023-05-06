<?php

namespace App\Services\Users;

use App\Models\User;
use App\Models\UserToken;
use App\Services\AbstractBaseInstanceService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class UserInstanceService extends AbstractBaseInstanceService
{
    public function __construct(User $user)
    {
        $this->modelClass = $user;
    }

    /**
     * @param array $attributes
     * @return Model
     */
    public function createInstance(array $attributes): Model
    {
        $attributes['password'] = bcrypt($attributes['password']);

        return parent::createInstance($attributes);
    }

    /**
     * @param int $id
     * @param array|string[] $attributes
     * @param array $with
     * @return array
     */
    public function getInstance(int $id, array $attributes = ['*'], array $with = []): array
    {
        $user = $this->modelClass::query()->find($id, $attributes);

        if (in_array('last_seen', $attributes, true)) {
            $user->append('is_online');
        }

        return $user ? $user->load($with)->toArray() : [];
    }

    /**
     * @param int $id
     * @param string $accessToken
     */
    public function setVkAccessToken(int $id, string $accessToken): void
    {
        UserToken::query()->updateOrCreate(['user_id' => $id, 'token' => $accessToken]);
    }

    /**
     * @param int $id
     * @param string $deviceName
     * @return array|null
     */
    public function getUserDevices(int $id, string $deviceName): ?array
    {
        $user = $this->modelClass::query()->find($id, ['id']);

        if (!$user) {
            return [];
        }

        return collect($user->getTokens())
            ->map(function (array $token) use ($deviceName) {
                if (!empty($token['last_used_at'])) {
                    $token['last_used_at'] = Carbon::parse($token['last_used_at'])->format('d.m.Y H:i');
                }

                $token['current_device'] = $token['name'] === $deviceName;

                return $token;
            })
            ->toArray();
    }
}