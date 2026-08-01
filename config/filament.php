<?php

return [
    'brand' => [
        'logo' => null,
        'favicon' => null,
    ],

    'default_filesystem_disk' => env('FILAMENT_FILESYSTEM_DISK', 'public'),

    'auth' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'pages' => [
            'login' => \Filament\Http\Livewire\Auth\Login::class,
        ],
    ],

    'home_url' => '/',

    'dark_mode' => false,
];