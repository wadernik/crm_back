<?php

declare(strict_types=1);

namespace App\Services\Order\Processor;

use App\Repositories\User\FindAllByInspectorInterface;
use function auth;

final class OrderInspectorProcessor implements OrderInspectorProcessorInterface
{
    public function __construct(private readonly FindAllByInspectorInterface $userRepository)
    {
    }

    public function process(): int
    {
        $users = $this->userRepository->findAllByInspector(asInspector: true);

        return $users->isEmpty() ? auth('sanctum')->id() : $users->first()->id;
    }
}