<?php

declare(strict_types=1);

namespace App\Console\Commands\User;

use App\DTOs\User\UpdateUserDTO;
use App\Helpers\UserCacheKeys;
use App\Managers\User\UserManagerInterface;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use function App\Helpers\Functions\load_service;
use function count;

final class UserOnlineCacheFlusherCommand extends Command
{
    private readonly UserRepositoryInterface $userRepository;
    private readonly UserManagerInterface $userManager;

    protected $signature = 'cache:user:update-online';

    protected $description = 'Update users online activity';

    public function __construct()
    {
        $this->userManager = load_service(UserManagerInterface::class);
        $this->userRepository = load_service(UserRepositoryInterface::class);

        parent::__construct();
    }

    public function handle(): void
    {
        $this->info('Updating users online activity');

        if (!Cache::has(UserCacheKeys::USER_ONLINE)) {
            $this->info('No user activity found in cache');

            return;
        }

        $cacheEntries = Cache::get(UserCacheKeys::USER_ONLINE);

        $progressBar = $this->output->createProgressBar(count($cacheEntries));

        foreach ($cacheEntries as $userId => $lastSeen) {
            $user = $this->userRepository->find($userId);

            if (!$user) {
                continue;
            }

            $this->userManager->update($user, new UpdateUserDTO(['last_seen' => $lastSeen]));

            $progressBar->advance();
        }

        $this->newLine();

        $progressBar->finish();

        Cache::forget(UserCacheKeys::USER_ONLINE);
    }
}