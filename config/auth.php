<?php

return [

    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        // Guard untuk tour leader (pakai sanctum/api)
        'tourleader' => [
            'driver' => 'sanctum',   // 🔑 kalau pakai Sanctum
            'provider' => 'tourleaders',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],

        // Provider untuk tour leader
        'tourleaders' => [
            'driver' => 'eloquent',
            'model' => App\Models\Tourleader::class,
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],

        // Password reset khusus tour leader (kalau mau pisah)
        'tourleaders' => [
            'provider' => 'tourleaders',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,

];
