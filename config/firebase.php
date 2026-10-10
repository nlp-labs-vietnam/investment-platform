<?php

return [
    'serverKey' => env('FIREBASE_SERVER_KEY'),
    'vapidKey' => env('FIREBASE_VAPID_KEY'),
    'apiKey' => env('FIREBASE_API_KEY'),
    'authDomain' => env('FIREBASE_AUTH_DOMAIN'),
    'projectId' => env('FIREBASE_PROJECT_ID'),
    'storageBucket' => env('FIREBASE_STORAGE_BUCKET'),
    'messagingSenderId' => env('FIREBASE_MESSAGING_SENDER_ID'),
    'appId' => env('FIREBASE_API_ID'),
    'measurementId' => env('FIREBASE_MEASUREMENT_ID'),
    'admin_foreground' => env('ADMIN_FOREGROUND'),
    'admin_background' => env('ADMIN_BACKGROUND'),
    'user_foreground' => env('USER_FOREGROUND'),
    'user_background' => env('USER_BACKGROUND'),
];

