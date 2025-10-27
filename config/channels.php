<?php

return [
    'notification' => [
        'sms' => [
            'driver' => env('SMS_DRIVER', 'log'),
            'from' => env('SMS_FROM', null),
            'provider' => [

            ]
        ]
    ]
];
