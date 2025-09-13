<?php

return [
    'base_uri' => env('INPOL_BASE_URI', 'https://inpol.mazowieckie.pl'),
    'username' => env('INPOL_USERNAME'),
    'password' => env('INPOL_PASSWORD'),
    // tune API rates (per minute)
    'rate_per_minute' => env('INPOL_RATE', 30),
];
