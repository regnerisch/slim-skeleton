<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Laminas\Diactoros\ServerRequestFactory;
use Psr\Log\LoggerInterface;
use Regnerisch\Skeleton\Services\ErrorHandler;
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

$middlewares = require __DIR__ . '/../config/middlewares.php';
$middlewares($app, $container);

$app->addRoutingMiddleware();

// Error handling
$isDev = 'development' === $_ENV['ENVIRONMENT'];
$errorMiddleware = $app->addErrorMiddleware($isDev, true, true);
$errorHandler = new ErrorHandler($app->getCallableResolver(), $app->getResponseFactory(), $container->get(LoggerInterface::class));
$errorMiddleware->setDefaultErrorHandler($errorHandler);

$routes = scandir(__DIR__ . '/../routes');
foreach ($routes as $route) {
    if (false !== stripos($route, '.php')) {
        require_once __DIR__ . '/../routes/' . $route;
    }
}

$request = ServerRequestFactory::fromGlobals();
$app->run($request);
