<?php

declare(strict_types=1);

use Middlewares\Cors;
use Middlewares\TrailingSlash;
use Neomerx\Cors\Analyzer;
use Neomerx\Cors\Strategies\Settings;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Slim\App;

return static function (App $app, ContainerInterface $container) {
    $app->add((new TrailingSlash())->redirect());

    $settings = $container->get('settings')['cors'];
    $corsSettings = new Settings();
    $corsSettings->enableCheckHost();
    $corsSettings->setLogger($container->get(LoggerInterface::class));
    $corsSettings->setServerOrigin($settings['scheme'], $settings['host'], $settings['port']);
    $corsSettings->setAllowedOrigins($settings['allowedOrigins']);
    $corsSettings->setAllowedMethods($settings['allowedMethods']);
    $corsSettings->setAllowedHeaders($settings['allowedHeaders']);
    $analyzer = Analyzer::instance($corsSettings);
    $app->add(new Cors($analyzer));
};
