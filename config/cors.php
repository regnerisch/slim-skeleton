<?php

declare(strict_types=1);

return [
    'cors' => [
        'scheme' => parse_url($_ENV['SERVER_ORIGIN'], PHP_URL_SCHEME),
        'host' => parse_url($_ENV['SERVER_ORIGIN'], PHP_URL_HOST),
        'port' => parse_url($_ENV['SERVER_ORIGIN'], PHP_URL_PORT),
        'allowedOrigins' => [],
        'allowedMethods' => [],
        'allowedHeaders' => [],
        'exposedHeaders' => [],
        // 'credentialsSupported' => false
        // 'preFlightCacheMaxAge' => 0
        // 'preFlightAddAllowedMethodsToResponse' => false
        // 'preFlightAddAllowedHeadersToResponse' => false
    ],
];
