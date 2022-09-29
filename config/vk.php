<?php

return [
    'url' => env('VK_OAUTH_URL'),
    'client_id' => env('VK_CLIENT_ID'),
    'client_secret' => env('VK_CLIENT_SECRET'),
    'group_ids' => trim(env('VK_GROUP_IDS')),
    'redirect_uri' => env('VK_REDIRECT_URI'),
];
