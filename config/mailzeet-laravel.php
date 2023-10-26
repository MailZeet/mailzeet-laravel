<?php

return [
    'queue'        => env('MAILZEET_QUEUE', 'default'),
    'apiKey'       => env('MAILZEET_API_KEY'),
    'env'          => env('MAILZEET_ENV', 'live'),
    'devMode'      => env('MAILZEET_DEV_MODE', false),
    'devBaseUrl'   => env('MAILZEET_DEV_BASE_URL', 'https://api.mailzeet.com'),
    'guzzleClient' => null,
];
