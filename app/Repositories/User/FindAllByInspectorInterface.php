<?php

declare(strict_types=1);

namespace App\Repositories\User;

use App\Models\User\User;
use Illuminate\Support\Collection;

interface FindAllByInspectorInterface
{
    /**
     * @param bool $asInspector
     *
     * @return Collection<User>
     */
    public function findAllByInspector(bool $asInspector = false): Collection;
}