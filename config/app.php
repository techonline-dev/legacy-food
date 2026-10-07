<?php

$appName = $_ENV['APP_NAME'] ?? $_SERVER['APP_NAME'] ?? getenv('APP_NAME') ?: 'Legacy Food';
$appEnv = $_ENV['APP_ENV'] ?? $_SERVER['APP_ENV'] ?? getenv('APP_ENV') ?: 'production';
$appDebug = filter_var($_ENV['APP_DEBUG'] ?? $_SERVER['APP_DEBUG'] ?? getenv('APP_DEBUG') ?: true, FILTER_VALIDATE_BOOLEAN);
$appUrl = rtrim((string)($_ENV['APP_URL'] ?? $_SERVER['APP_URL'] ?? getenv('APP_URL') ?: 'https://lensinteractive.com/legacyfood'), '/');

return [
    'name' => $appName,
    'env' => $appEnv,
    'debug' => $appDebug,
    'url' => $appUrl,
    'timezone' => 'Asia/Kolkata',
    'locale' => 'en',
    'currency' => 'INR',
    'currency_symbol' => '₹',
];
