<?php

use Slim\App;
use Middlewares\TrailingSlash;
use Psr\Container\ContainerInterface;
use Neomerx\Cors\Strategies\Settings;
use Psr\Log\LoggerInterface;
use Neomerx\Cors\Analyzer;
use Middlewares\Cors;

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
