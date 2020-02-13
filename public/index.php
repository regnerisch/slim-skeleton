<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Laminas\Diactoros\ServerRequestFactory;
use Slim\Factory\AppFactory;
use Symfony\Component\Dotenv\Dotenv;

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '/../.env');
$containerBuilder = new ContainerBuilder();

$settings = require __DIR__ . '/../config/settings.php';
$settings($containerBuilder);

$dependencies = require __DIR__ . '/../config/dependencies.php';
$dependencies($containerBuilder);

$container = $containerBuilder->build();

AppFactory::setContainer($container);
$app = AppFactory::create();

$app->addRoutingMiddleware();

$middlewares = require __DIR__ . '/../config/middlewares.php';
$middlewares($app, $container);

$routes = scandir(__DIR__ . '/../routes');
foreach ($routes as $route) {
    if (false !== stripos($route, '.php')) {
        require_once __DIR__ . '/../routes/' . $route;
    }
}

$request = ServerRequestFactory::fromGlobals();
$app->run($request);
