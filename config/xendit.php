<?php

return [
    'secret_key' => env('XENDIT_SECRET_KEY'),
    'public_key' => env('XENDIT_PUBLIC_KEY'),
    'environment' => env('XENDIT_ENV', 'sandbox'),
    'api_version' => '2022-07-31',
];