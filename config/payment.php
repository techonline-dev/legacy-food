<?php

return [
    'currency' => 'INR',
    'razorpay' => [
        'enabled' => true,
        'key_id' => getenv('RAZORPAY_KEY_ID') ?: 'rzp_test_legacyfood123',
        'key_secret' => getenv('RAZORPAY_KEY_SECRET') ?: 'legacyfood_secret_demo',
    ],
    'cashfree' => [
        'enabled' => false,
        'app_id' => getenv('CASHFREE_APP_ID') ?: '',
        'secret_key' => getenv('CASHFREE_SECRET_KEY') ?: '',
    ],
    'cod' => [
        'enabled' => true,
        'extra_charge' => 0.00,
    ]
];
