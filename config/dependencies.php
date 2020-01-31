<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Regnerisch\Skeleton\Services\Renderer;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

return static function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        LoggerInterface::class => static function (ContainerInterface $container) {
            $settings = $container->get('settings')['logger'];

            $logger = new Logger($settings['name']);

            $handler = new StreamHandler($settings['path'], $settings['level']);

            $logger->pushHandler($handler);

            return $logger;
        },
        Environment::class => static function (ContainerInterface $container) {
            $loader = new FilesystemLoader(__DIR__ . '/../templates');
            $twig = new Environment($loader);

            return $twig;
        },
        Renderer::class => static function (ContainerInterface $container) {
            return new Renderer($container->get(Environment::class), $container->get(LoggerInterface::class));
        },
    ]);
};
