<?php

namespace App\Services\Users;

use App\Models\User;
use App\Services\AbstractBaseInstanceService;
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
}
