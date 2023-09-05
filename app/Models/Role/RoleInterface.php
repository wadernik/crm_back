<?php

declare(strict_types=1);

namespace App\Models\Role;


use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

/**
 * @property int    $id
 * @property string $name
 * @property string $label
 *
 * @property Collection $permissions
 */
interface RoleInterface
{
    public function permissions(): BelongsToMany;
}