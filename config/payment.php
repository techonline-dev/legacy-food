<?php

$readEnv = static function (string $key, ?string $default = null): ?string {
    $value = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key);
    if ($value === false || $value === null || $value === '') {
        return $default;
    }
    return (string)$value;
};

$appEnv = strtolower($readEnv('APP_ENV', 'production') ?? 'production');
$demoDefault = !in_array($appEnv, ['production', 'prod'], true);

return [
    'currency' => 'INR',
    'demo' => filter_var($readEnv('PAYMENT_DEMO', $demoDefault ? 'true' : 'false'), FILTER_VALIDATE_BOOLEAN),
    'razorpay' => [
        'enabled' => true,
        'key_id' => $readEnv('RAZORPAY_KEY_ID', ''),
        'key_secret' => $readEnv('RAZORPAY_KEY_SECRET', ''),
    ],
    'cashfree' => [
        'enabled' => false,
        'app_id' => $readEnv('CASHFREE_APP_ID', ''),
        'secret_key' => $readEnv('CASHFREE_SECRET_KEY', ''),
    ],
    'cod' => [
        'enabled' => true,
        'extra_charge' => 0.00,
    ]
];
