<?php

use App\Models\Board\Board;
use App\Models\User\User;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/
Broadcast::channel('users.{id}', static function (User $user, int $id) {
    return $user->id === $id;
});

Broadcast::channel('boards.{id}', static function (User $user, int $id) {
    /** @var Board $board */
    $board = Board::query()->find($id);

    if (!$board) {
        return false;
    }

    foreach ($board->users as $authorizedUser) {
        if ($authorizedUser->id === $user->id) {
            return true;
        }
    }

    return false;
});