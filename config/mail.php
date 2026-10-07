<?php

return [
    'host' => getenv('MAIL_HOST') ?: 'smtp.gmail.com',
    'port' => getenv('MAIL_PORT') ?: 587,
    'username' => getenv('MAIL_USERNAME') ?: '',
    'password' => getenv('MAIL_PASSWORD') ?: '',
    'encryption' => getenv('MAIL_ENCRYPTION') ?: 'tls',
    'from' => [
        'address' => getenv('MAIL_FROM_ADDRESS') ?: 'contact@legacyfood.in',
        'name' => getenv('MAIL_FROM_NAME') ?: 'Legacy Food'
    ]
];
