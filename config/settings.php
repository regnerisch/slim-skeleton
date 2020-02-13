<?php

declare(strict_types=1);

use DI\ContainerBuilder;

return static function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        'settings' => [
            'environment' => $_ENV['ENVIRONMENT'],
            'logger' => [
                'name' => 'Monolog',
                'path' => __DIR__ . '/../var/logs/logs.log',
                'level' => (int) $_ENV['LOG_LEVEL'],
            ],
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
        ],
    ]);
};
