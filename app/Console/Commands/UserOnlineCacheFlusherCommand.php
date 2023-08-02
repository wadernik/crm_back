<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\DTOs\User\UpdateUserDTO;
use App\Helpers\UserCacheKeys;
use App\Managers\User\UserManagerInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use function count;

final class UserOnlineCacheFlusherCommand extends Command
{
    private UserManagerInterface $userManager;

    protected $signature = 'user:cache:update-online';

    protected $description = 'Update users online activity';

    public function __construct()
    {
        $this->userManager = app(UserManagerInterface::class);

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
            $userDto = new UpdateUserDTO(['last_seen' => $lastSeen]);

            $this->userManager->update($userId, $userDto);

            $progressBar->advance();
        }

        $progressBar->finish();

        Cache::forget(UserCacheKeys::USER_ONLINE);
    }
}