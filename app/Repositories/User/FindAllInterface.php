<?php

declare(strict_types=1);

namespace App\Repositories\User;

use App\Models\User\User;
use Illuminate\Support\Collection;

interface FindAllInterface
{
    /**
     * @return Collection<User>
     */
    public function findAll(): Collection;
}