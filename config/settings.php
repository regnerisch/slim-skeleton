<?php

use DI\ContainerBuilder;
use Monolog\Logger;

return static function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        'settings' => [
            'environment' => 'development',
            'logger' => [
                'name' => 'Monolog',
                'path' => __DIR__ . '/../var/logs/logs.log',
                'level' => Logger::DEBUG,
            ],
            'cors' => [
                'scheme' => 'http',
                'host' => 'localhost',
                'port' => 8080,
                'allowedOrigins' => [],
                'allowedMethods' => [],
                'allowedHeaders' => [],
            ]
        ]
    ]);
};
