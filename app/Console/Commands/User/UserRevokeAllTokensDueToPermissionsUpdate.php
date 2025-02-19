<?php

declare(strict_types=1);

namespace App\Console\Commands\User;

use App\Models\User\User;
use App\Services\Auth\AuthUserServiceInterface;
use Illuminate\Console\Command;

final class UserRevokeAllTokensDueToPermissionsUpdate extends Command
{
    protected $signature = 'user:revoke-all-tokens';

    protected $description = 'Revoke all tokens from all users due to permissions update';

    public function handle(AuthUserServiceInterface $authUserService): void
    {
        $usersAmount = User::count();

        $this->info("$usersAmount users found. Revoking tokens by chunks of users");

        $progressBar = $this->output->createProgressBar($usersAmount);

        foreach (User::all()->chunk(100) as $chunk) {
            /** @var User $user */
            foreach ($chunk as $user) {
                $authUserService->revokeAllTokensByUser($user);
            }

            $progressBar->advance();
        }

        $progressBar->finish();
    }
}