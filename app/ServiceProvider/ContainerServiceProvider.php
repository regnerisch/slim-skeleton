<?php

declare(strict_types=1);

namespace App\ServiceProvider;

use DI\ContainerBuilder;
use League\Event\Emitter;
use League\Event\EmitterInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Noodlehaus\Config;
use Noodlehaus\ConfigInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

class ContainerServiceProvider
{
    public function __invoke(ContainerBuilder $containerBuilder)
    {
        $containerBuilder->addDefinitions([
            ConfigInterface::class => static function (ContainerInterface $container) {
                return new Config(ABS_PATH . '/config');
            },
            LoggerInterface::class => static function (ContainerInterface $container) {
                $config = $container->get(ConfigInterface::class);

                $logger = new Logger($config->get('logger.name'));

                $handler = new StreamHandler($config->get('logger.path'), $config->get('logger.level'));

                $logger->pushHandler($handler);

                return $logger;
            },
            EmitterInterface::class => static function (ContainerInterface $container) {
                return new Emitter();
            },
        ]);

        if ('production' === $_ENV['APP_ENV']) {
            $containerBuilder->enableCompilation(ABS_PATH . '/var/php-di/tmp');
            $containerBuilder->writeProxiesToFile(true, ABS_PATH . '/var/php-di/proxies');
        }

        return $containerBuilder->build();
    }
}
