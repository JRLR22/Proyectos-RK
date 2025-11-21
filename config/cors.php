<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie', 'img/*', 'storage/*'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['*'], // En producción, pon solo el dominio
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
];