<?php

return [
    'panels' => [
        'admin' => [
            'enabled' => env('ADMIN_PANEL_LOCAL_LOGINS_ENABLED', env('APP_ENV') === 'local'),
            'emails' => array_filter(array_map('trim', explode(',', env('ADMIN_PANEL_LOCAL_LOGIN_EMAILS', '')))),
        ],
    ],
];
