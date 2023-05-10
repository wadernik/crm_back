<?php

declare(strict_types=1);

namespace App\Models\User;

use App\Models\Role\RoleInterface;
use BeyondCode\Comments\Contracts\Commentator;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;

/**
 * @property string $first_name
 * @property string $last_name
 * @property string $username
 * @property string $password
 * @property int    $role_id
 * @property string $phone
 * @property string $email
 * @property string $birth_date
 * @property int    $sex,
 * @property string $last_seen
 * @property string $remember_token
 *
 * @property RoleInterface $role
 */
interface UserInterface extends Commentator
{
    public function getTokens(): Collection;

    public function role(): BelongsTo;

    public function vkAccessToken(): HasOne;

    public function getUserPermissions(): Collection;
}