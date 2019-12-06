<?php

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;
use Zend\Diactoros\ServerRequestFactory;

require_once __DIR__ . '/../vendor/autoload.php';

$containerBuilder = new ContainerBuilder();

$settings = require __DIR__ . '/../config/settings.php';
$settings($containerBuilder);

$dependencies = require __DIR__ . '/../config/dependencies.php';
$dependencies($containerBuilder);

$container = $containerBuilder->build();

AppFactory::setContainer($container);
$app = AppFactory::create();

$middlewares = require __DIR__ . '/../config/middlewares.php';
$middlewares($app, $container);

$app->addRoutingMiddleware();

$routes = scandir(__DIR__ . '/../routes');
foreach ($routes as $route) {
    if (false !== stripos($route, '.php')) {
        require_once __DIR__ . '/../routes/' . $route;
    }
}

$request = ServerRequestFactory::fromGlobals();
$app->run($request);
