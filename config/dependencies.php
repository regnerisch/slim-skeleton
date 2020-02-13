<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

return static function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        LoggerInterface::class => static function (ContainerInterface $container) {
            $settings = $container->get('settings')['logger'];

            $logger = new Logger($settings['name']);

            $handler = new StreamHandler($settings['path'], $settings['level']);

            $logger->pushHandler($handler);

            return $logger;
        },
        EntityManagerInterface::class => static function (ContainerInterface $container) {
            $settings = $container->get('settings')['doctrine'];

            $isDev = 'development' === getenv('ENVIRONMENT');
            $config = Setup::createAnnotationMetadataConfiguration(
                [__DIR__ . '/../app/Entity'],
                $isDev,
                __DIR__ . '/../var/proxy',
                null,
                false
            );

            $connectionParams = [
                'url' => $settings['url']
            ];

            return EntityManager::create($connectionParams, $config);
        }
    ]);
};
