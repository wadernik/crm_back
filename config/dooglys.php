<?php

return [
    'api_url' => env('DOOGLYS_API_URL', ''),
    'access_token' => env('DOOGLYS_ACCESS_TOKEN', ''),
    'retries_amount' => env('DOOGLYS_REQUEST_RETRIES_AMOUNT', 1),
    'retry_timeout' => env('DOOGLYS_RETRY_TIMEOUT', 30),
];