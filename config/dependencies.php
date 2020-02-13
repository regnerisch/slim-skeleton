<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

return static function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        LoggerInterface::class => static function (ContainerInterface $container) {
            $settings = $container->get('settings')['logger'];

            $logger = new Logger($settings['name']);

            $handler = new StreamHandler($settings['path'], $settings['level']);

            $logger->pushHandler($handler);

            return $logger;
        },
    ]);
};
