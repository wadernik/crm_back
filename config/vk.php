<?php

return [
    'url' => env('vk_oauth_url'),
    'client_id' => env('vk_client_id'),
    'client_secret' => env('vk_client_secret'),
    'group_ids' => trim(env('vk_group_ids')),
    'redirect_uri' => env('vk_redirect_uri'),
];
