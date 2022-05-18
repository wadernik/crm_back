<?php

namespace App\Services;

use App\Services\Traits\JoinableTrait;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractBaseInstanceService
{
    use JoinableTrait;

    protected Model $modelClass;
    protected array $joins = [];

    /**
     * @param int $id
     * @param array|string[] $attributes
     * @param array $with
     * @return array
     */
    public function getInstance(int $id, array $attributes = ['*'], array $with = []): array
    {
        $query = $this->modelClass::query();
        $this->joinTables($query, $this->joins);

        $instance = $query->find($id, $attributes);

        return $instance ? $instance->load($with)->toArray() : [];
    }

    /**
     * @param array $attributes
     * @return Model
     */
    public function createInstance(array $attributes): Model
    {
        return $this->modelClass::query()->create($attributes);
    }

    /**
     * @param int $id
     * @param array $attributes
     * @return Model|null
     */
    public function editInstance(int $id, array $attributes): ?Model
    {
        if (!$instance = $this->modelClass::query()->find($id)) {
            return null;
        }

        $instance->update($attributes);

        return $instance;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function deleteInstance(int $id): bool
    {
        if (!$instance = $this->modelClass::query()->find($id)) {
            return false;
        }

        $instance->delete();

        return true;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function forceDeleteInstance(int $id): bool
    {
        $instance = $this->modelClass::query()
            ->withTrashed()
            ->find($id);

        if (!$instance) {
            return false;
        }

        $instance->forceDelete();

        return true;
    }
}
