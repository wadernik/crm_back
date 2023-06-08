<?php

declare(strict_types=1);


namespace App\DTOs\User;

use Illuminate\Contracts\Support\Arrayable;

interface UserReportDTOInterface extends Arrayable
{
    public function userId(): ?string;

    public function roleId(): ?string;

    public function dateStart(): string;

    public function dateEnd(): ?string;
}